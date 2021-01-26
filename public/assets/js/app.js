$(document).ready(function() {
});

const flash = (message, type = 'success', close = true) => {
    close = close === true ? `<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                   </button>` : '';
    let alert =    `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <strong>${message}</strong>
                        ${close}
                   </div>`;
    document.querySelector('.flash').innerHTML = alert;
}

const openModal = (event) => {
    event.preventDefault();
    document.querySelector('.dialog-container').classList.toggle('hidden');
}

const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' });

const displayScrollTop = () => {
    let scrollBtn = document.querySelector("#scrollTop");
    let target = window.innerHeight * 1.7;
    let scroll = document.querySelector('html').scrollTop;
    let bottom = parseInt(scroll + innerHeight);
    if ( bottom > target ) {
        return scrollBtn.classList.remove("hidden");
    }
    return scrollBtn.classList.add("hidden");
}

const stickHeader = () => {
    let scrollTop = document.querySelector('html').scrollTop;
    let navBar = document.querySelector('#navTop');
    if ( scrollTop > (window.innerHeight * 1.2) ) {
        return navBar.style.position = "fixed";
    }
    return navBar.style.position = "static";
}

const displayMenu = () => {
    let menu = document.getElementById('menu-mobile');
    if ( menu.classList.contains('show') ) return menu.classList.replace('show', 'hide');
    menu.classList.remove('hide'); 
    menu.classList.add('show');
    return; 
}