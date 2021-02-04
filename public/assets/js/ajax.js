const handleContact = (form, e) => {
    e.preventDefault();
    let url = form.action;
    let data = getValues('.form-control'); 

    axios
    .post(url, data)
    .then( ({data}) => {
        console.log(data)
        if ( data.hasOwnProperty('message') ) {
            let type = data.message.type; 
            let message = data.message.content;
            flash(message, type, true);
            console.warn(data);
            if (data.hasOwnProperty('url')) {
                let url = data.url;
                let delay = 2000; 
                // Faire une redirection sur la page d'édition de l'article
                setTimeout(() => window.location = url , delay);
            }                
        } 
    })
    .catch( ({response}) => {
        let type = response.data.message.type; 
        let message = response.data.message.content;    
        if ((response.status).toString().indexOf('4') === 0) flash(message, type, true);
        if ((response.status).toString().indexOf('5') === 0) flash(message, type, true);
   });
}

const getValues = selector => {
    let inputs = document.querySelectorAll(selector);
    let values = {};
    inputs.forEach(input => {
        values[input.name] = input.value;
    });
    return values;
}

const setProjectDialog = (data) => {
    let technos = '';
    let img = document.querySelector('.img-dialog');
    let techno = document.querySelector('.dialog-techno')
    document.querySelector('.dialog-title').innerText = data.title;
    img.setAttribute('src', `/uploads/${data.image}`)
    img.setAttribute('srcset', `/uploads/${data.image}`)
    document.querySelector('.dialog-details').innerHTML = data.details;
    document.querySelector('.dialog-link').setAttribute('href', data.link);
    (data.techno).forEach((tech) => {
        technos += `<li>${tech}</li>`;
    });
    techno.innerHTML = technos;
    return;
}

const load = () => {
    let load = document.getElementById('load');
    let loader = `<div class="lds-ring"><div></div><div></div><div></div><div></div></div>`;
    load.classList.remove('hidden');
    load.innerHTML = loader;
}

const unload = () => {
    let load = document.getElementById('load');
    load.classList.add('hidden');    
    load.innerHTML = '';
}

const getProject = (btn, event) => {
    event.preventDefault();
    load();
    console.log(btn);
    const url = btn.href; 
    axios 
    .get(url)
    .then( ({data}) => {
        console.warn(data);
        setProjectDialog(data.project);
        unload()
    })
    .catch( ({response}) => {
        console.error(response)
        if (response.hasOwnProperty('data')) { 
            if ((response.status).toString().indexOf('4') === 0) flash(response.data.message.content, response.data.message.type, true);
            if ((response.status).toString().indexOf('5') === 0) flash(response.data.message.content, response.data.message.type, true);
        }
        unload()
    });
}