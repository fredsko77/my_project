<?php
namespace App\Services;

use Slim\Psr7\UploadedFile;

class Uploads
{

    /**
     * @var string $targetPath
     */
    private $targetPath;

    /**
     * @var UploadedFile $uploadedFile
     */
    private $uploadedFile;

    const MAX_FILE_SIZE = 10; // File size MB

    const FILE_ACCEPTED = ['jpeg', 'gif', 'jpg', 'png', 'svg'];

    public function __construct(UploadedFile $uploadedFile)
    {
        $this->uploadedFile = $uploadedFile;
        $this->targetPath = './uploads';
    }

    public function upload()
    {
        if (!$this->isDir()) {
            mkdir($this->targetPath, 0777, true);
        }
        $errors = [];
        if (!$this->checkExtension()) {
            $errors['extension'] = 'Votre image doit être au format .jpg, .jpeg, .svg, .gif ou .png';
        }
        if ($this->mb() > self::MAX_FILE_SIZE) {
            $errors['extension'] = 'Votre fichier est trop lourd. Poids max 10mb. ';
        }
        if (count($errors) > 0) {

            return $errors;
        }

        $filename = $this->filename();

        $this->uploadedFile->moveTo($this->targetPath . '/' . $filename);

        return $this->targetPath . '/' . $filename;
    }

    /**
     * @return bool
     */
    public function isDir(): bool
    {
        if (!file_exists($this->targetPath)) {
            return false;
        }
        return true;
    }

    /**
     * @return float $bytes
     */
    public function mb(): float
    {
        return number_format($this->uploadedFile->getSize() / 1048576, 2);
    }

    public function checkExtension()
    {
        $filename = $this->uploadedFile->getClientFilename();
        $file_extension = strtolower(explode('.', $filename)[1]);
        return in_array($file_extension, self::FILE_ACCEPTED);
    }

    public function filename()
    {
        $filename = $this->uploadedFile->getClientFilename();
        $file_extension = strtolower(explode('.', $filename)[1]);
        return generate_filename() . '.' . $file_extension;
    }

}
