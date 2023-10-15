document.addEventListener("DOMContentLoaded", function() {
    let tabContents = document.querySelectorAll('.tabcontent');
    let tabLinks = document.querySelectorAll('.tablinks');
    if (tabContents.length > 0 && tabLinks.length > 0) {
        tabContents[0].style.display = "block";
        tabLinks[0].classList.add("active");
    } else {
        console.error("Elements with class .tabcontent or .tablinks not found.");
    }
    //form ajax
    let SDO = document.getElementById('sdo');
    let form = document.getElementById('save-options-sdo');
    let errorText = form.querySelector('.error-text');
    let successText = form.querySelector('.success-text');
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        SDO.classList.add('loading');
        errorText.style.display = 'none';
        successText.style.display = 'none';
        let formData = new FormData(form);
        let checkboxes = form.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            let checkboxName = checkbox.name;
            if (!formData.has(checkboxName)) {
                formData.set(checkboxName, 'off');
            }
        });
        formData.append('action', 'save_sdo_data');
        formData.append('security', data_sdo.nonce);
        let xhr = new XMLHttpRequest();
        xhr.open('POST', data_sdo.ajax_url, true);
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onload = function() {
            let response = JSON.parse(xhr.responseText);
            if (xhr.status === 200) {
                SDO.classList.remove('loading');
                if (response.success == true) {
                    successText.textContent = response.data.message;
                    successText.style.display = 'block';
                } else {
                    errorText.textContent = response.data.message;
                    errorText.style.display = 'block';
                }
            } else {
                console.error('Request failed with status:', xhr.status);
            }
        };
        xhr.onerror = function() {
            console.error('Request failed');
        };
        xhr.send(formData);
    });

});
function openTabSDO(evt, tabName) {
    let i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
}