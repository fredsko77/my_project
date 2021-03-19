const handleContact = (form, e) => {
    e.preventDefault();

    const url = form.action;
    const data = getValues('.form-control');

    axios
        .post(url, data)
        .then(({ data }) => {
            if (data.hasOwnProperty('errors')) {
                setErrors(data.errors);
            }
            if (data.hasOwnProperty('message')) {
                flash(data.message.content, data.message.type, true);
            }
        })
        .catch(({ response }) => {
            let type = response.data.message.type;
            let message = response.data.message.content;
            if ((response.status).toString().indexOf('4') === 0) flash(message, type, true);
            if ((response.status).toString().indexOf('5') === 0) flash(message, type, true);
        });
}