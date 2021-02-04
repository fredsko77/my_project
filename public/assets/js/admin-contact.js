const setContactContainer = data => {
  let html = `
  <div class="message-header">
    <h5 class="message-title">${data.object}</h5>
    <span aria-close onclick="closeMessageContainer()"><i class="fas fa-times"></i></span>
  </div>
  <div class="message-body">                    
    <div>
      <h5>Nom :</h5>
      <p>${data.name}</p>
      <h5>Email :</h5>
      <p>${data.email}</p>
      <h5>Message</h5>
      <p>${data.message}</p>
    </div>
    <!-- à implémenter dans une version suivante -->
    <a class="btn btn-primary" href="">Répondre</a>
  </div>
  `;

  let container = document.querySelector('[data-message]');

  return container.innerHTML = html;
}

const displayMessageContainer = () => {
  let container = document.querySelector('.message-container');
  if ( container.classList.contains('hide') ) {
    return container.classList.replace('hide', 'show');
  }
  return container.classList.add('show');
}

const closeMessageContainer = () => {
  let container = document.querySelector('.message-container');
  document.querySelector('[data-message]').innerHTML = "";
  return container.classList.replace('show', 'hide');
}

const getContact = (link, event) => {
  event.preventDefault();
  let url = link.href;
  axios
  .get(url)
  .then( ({data}) => {
    console.log(data);
    if (data.hasOwnProperty('contact')) {
      setContactContainer(data.contact);
      displayMessageContainer();
    }
    if ( data.hasOwnProperty('message') ) {
        let type = data.message.type; 
        let message = data.message.content;
        flash(message, type, true);
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