document.forms['user'].addEventListener('submit', function(e){
    let error;
    const inputs = this;

    const errorMessage = {
        firstname: 'Caractères : Min.3-Max.40, seul les lettres, les tirets et les espaces sont autorisés',
        lastname: 'Caractères : Min.3-Max.90, seul les lettres, les tirets et les espaces sont autorisés',
        email: 'Veuillez saisir une adresse email valide',
        password: 'Le mot de passe doit contenir au moins 8 caractères dont 1 majuscule, 1 minuscule, 1 chiffre et 1 caractère spécial',
        confirmPassword: 'Les mots de passe ne correspondent pas',
    };

    const setErrorField = (formDivId, errorTarget) => {
        e.preventDefault();
        if(!document.querySelector("."+formDivId)){
            let newElement = document.createElement("p");
            newElement.classList.add(formDivId, "text-danger");
            newElement.innerHTML = errorMessage[errorTarget];
            let domSelector = document.getElementById(formDivId);
            domSelector.append(newElement);
        }
    }

    const removeErrorField = (classname) => {
        let domSelector = document.querySelector(classname);
        if(domSelector){
            domSelector.remove();
        }
    }

    if(!inputs['user[firstname]'].value.match(/^[a-z-\s]{3,40}$/i)){
        setErrorField('firstname', 'firstname');
    } else {
        removeErrorField('.firstname');
    }

    if(!inputs['user[lastname]'].value.match(/^[a-z-\s]{3,90}$/i)){
        setErrorField('lastname', 'lastname');
    } else {
        removeErrorField('.lastname');
    }

    if(!inputs['user[email]'].value.match(/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/)){
        setErrorField('email', 'email');
    } else {
        removeErrorField('.email');
    }

    if(inputs['user[plainPassword][first]'].value){
        if(!inputs['user[plainPassword][first]'].value.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/i)){
            setErrorField('password', 'password');
        } else {
            removeErrorField('.password');
        }
    }

    if(inputs['user[plainPassword][second]'].value != inputs['user[plainPassword][first]'].value){
        setErrorField('confirm-password', 'confirmPassword');
    } else {
        removeErrorField('.confirm-password');
    }

})