var header = {
    'Authorization': localStorage.getItem('token'),
};

const handleProject = (form, e) => {
    e.preventDefault();
    const url = form.action;

    const data = new FormData(form);

    axios
        .post(url, data, { headers: header })
        .then(({ data }) => {
            if (data.hasOwnProperty('message')) {
                flash(data.message.content, data.message.type, true);
            }
            if (data.hasOwnProperty('url')) {
                // Faire une redirection vers l'url reçu
                setTimeout(() => window.location = data.url, 3000);
            }
            if (data.hasOwnProperty('errors')) {
                setErrors(data.errors);
            }
            if (data.hasOwnProperty('token')) {
                localStorage.set('token', data.token);
            }

            delete document.querySelector('input[type=file]').value
        })
        .catch(({ response }) => {
            let type = response.data.message.type;
            let message = response.data.message.content;
            if ((response.status).toString().indexOf('4') === 0) flash(message, type, true);
            if ((response.status).toString().indexOf('5') === 0) flash(message, type, true);
        });
}

const deleteProject = (link, e) => {
    e.preventDefault();
    url = link.href;
    axios
        .delete(link, { headers: header })
        .then(({ data }) => {
            if (data.hasOwnProperty('message')) {
                flash(data.message.content, data.message.type, true);
            }
            if (data.hasOwnProperty('url')) {
                // Faire une redirection vers l'url reçu
                setTimeout(() => window.location = data.url, 3000);
            }
            if (data.hasOwnProperty('deleted')) {
                document.querySelector('[data-id="' + data.deleted + '"]').remove();
            }

        })
        .catch(({ response }) => {
            let type = response.data.message.type;
            let message = response.data.message.content;
            if ((response.status).toString().indexOf('4') === 0) flash(message, type, true);
            if ((response.status).toString().indexOf('5') === 0) flash(message, type, true);
        });
}

const getCheckedValue = selector => {
    const checkBoxes = document.querySelectorAll(selector);
    const values = [];

    checkBoxes.forEach((el, index) => {
        if (el.checked === true) values.push(el.value);
        console.log({ el, index })
    })

    return values;
}

jQuery(function($) {

    $('.checkBtn').each(function() {
        $(this).on('click', function() {
            $label = $(this).attr('for');
            $checkedValue = $('input[id=' + $label + ']')[0];
            $(this).toggleClass('active');
        });
    });

});

const streamImage = (elt, e) => {
    const imageContainer = document.querySelector('#uploadedFile');
    const errorMessage = document.querySelector('#uploadError');
    const acceptedFile = ["gif", "jpg", "jpeg", "png", "svg"];
    if (elt.files && elt.files[0]) {
        let filename = elt.files[0].name;
        let extension = (filename.split('.')[1]).toLowerCase();
        if (acceptedFile.includes(extension)) {
            imageContainer.classList.remove('hidden');
            imageContainer.src = URL.createObjectURL(e.target.files[0]);
            imageContainer.srcset = URL.createObjectURL(e.target.files[0]);
            imageContainer.onload = () => URL.revokeObjectURL(imageContainer.src); // Free memory 
            errorMessage.classList.add('hidden');
            errorMessage.innerHTML = '';
        } else {
            imageContainer.classList.add('hidden');
            errorMessage.classList.remove('hidden');
            errorMessage.innerHTML = `Seuls les fichiers .gif, .jpg, .jpeg, .png ou .svg sont acceptés, votre fichier est fichier <i>.${extension}</i>`
        }
    } else {
        imageContainer.classList.add('hidden');
    }

}

const addTask = () => {

    let container = document.getElementById('task-group');

    const counter = container.querySelectorAll('.task').length;
    const id = parseInt(counter) + 1;

    let newRow = document.createElement('div');
    newRow.classList.add('row', 'task', 'align-items-baseline', "mb-1");
    newRow.setAttribute('data-task', id);

    let html = `
        <input id="mes_taches_${id}" class="form-control col-11" type="text" name="tasks[]">
        <span class="col-1">
            <i class="icofont-close cursor-pointer font-weight-bold" title="Supprimer cette tache" onclick="deleteTask(${id})"></i>
        </span>
    `;

    newRow.innerHTML = html;

    return container.appendChild(newRow); //container.innerHTML(newRow);
}

const deleteTask = (id) => {
    const task = document.querySelector(`[data-task="${id}"]`);
    return task.remove();
}