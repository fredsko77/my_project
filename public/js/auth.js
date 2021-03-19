const handleRequest = (form, e) => {
    e.preventDefault();
    const url = form.action;

    const data = getValues('.form-control');

    axios
        .post(url, data)
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
                localStorage.setItem('token', data.token);
            }
        })
        .catch(({ response }) => {
            let type = response.data.message.type;
            let message = response.data.message.content;
            if ((response.status).toString().indexOf('4') === 0) flash(message, type, true);
            if ((response.status).toString().indexOf('5') === 0) flash(message, type, true);
        });
    console.log({ data })
}