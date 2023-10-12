document.addEventListener("DOMContentLoaded", function() {
    var tabContents = document.querySelectorAll('.tabcontent');
    var tabLinks = document.querySelectorAll('.tablinks');
    if (tabContents.length > 0 && tabLinks.length > 0) {
        tabContents[0].style.display = "block";
        tabLinks[0].classList.add("active");
    } else {
        console.error("Elements with class .tabcontent or .tablinks not found.");
    }
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