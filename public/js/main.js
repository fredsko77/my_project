const displayMenuMobile = (button) => {
    const closeIcon = `<i class="icofont-close"></i>`;
    const navIcon = `<i class="icofont-navigation-menu"></i>`;
    const close = button.getAttribute('aria-close');
    const navMenu = document.getElementById('menu-mobile');
    if (close === "false") {
        button.setAttribute('aria-close', 'true');
        button.innerHTML = closeIcon;
        return navMenu.classList.toggle('show');
    }

    button.setAttribute('aria-close', 'false');
    button.innerHTML = navIcon;
    return navMenu.classList.toggle('show');
    // console.log({ button, closeIcon, close, navIcon, navMenu });
}

const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' });

const displayScrollTop = () => {
    let scrollBtn = document.querySelector("#scrollTop");
    let target = window.innerHeight * 1.7;
    let scroll = document.querySelector('html').scrollTop;
    let bottom = parseInt(scroll + innerHeight);
    if (bottom > target) {
        return scrollBtn.classList.remove("hidden");
    }
    return scrollBtn.classList.add("hidden");
}

const stickHeader = () => {
    let scrollTop = document.querySelector('html').scrollTop;
    let navBar = document.querySelector('header');

    if (scrollTop > (window.innerHeight * .9)) {
        setStyle(navBar, { position: "fixed", });
        return;
    }
    setStyle(navBar, { position: "static", });
    return;
}

const setStyle = (element, style = {}) => {

    // CSS properties
    let properties = '';

    for (const property in style) {
        if (style.hasOwnProperty(property)) {
            properties += `${property}: ${style[property]}; `;
        }
    }

    if (element instanceof NodeList) {
        element.forEach(elt => elt.setAttribute('style', properties));
        return;
    }

    // Set css attributes to an element
    return element.setAttribute('style', properties);
}

const flash = (message, type = 'success', close = true) => {
    close = close === true ? `<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                   </button>` : '';
    let alert = `<div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <strong>${message}</strong>
                        ${close}
                   </div>`;
    document.querySelector('.flash').innerHTML = alert;
    setTimeout(() => {
        document.querySelector('[role="alert"]').classList.replace('show', 'hide')
    }, 5000);
}

const getValues = selector => {
    let object = {};
    elements = document.querySelectorAll(selector);
    elements.forEach(element => {
        object[element.name] = element.value;
    });
    return object;
}

const setErrors = (errors = {}) => {
    let listErrors = '';
    let container = document.getElementById('alert');
    let list = container.querySelector('.errors');

    if (Object.keys(errors).length > 0) {
        for (const key in errors) {
            if (errors.hasOwnProperty(key)) {
                listErrors += `<li> ${key}: ${errors[key]} </li>`
            }
        }
        container.classList.remove('hidden');
        return list.innerHTML = listErrors;
    }

    if (container.classList.contains('hidden')) {
        return;
    }

    return container.classList.add('hidden');

}