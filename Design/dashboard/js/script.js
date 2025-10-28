const navLinks = document.querySelectorAll(".nav-link");

navLinks.forEach(link => {
    if (link.closest(".logout")) {
        return;
    }
    
    link.addEventListener("click", (e) => {
        if (link.getAttribute("href") !== "#") {
            return; 
        }
        
        e.preventDefault();
        
        navLinks.forEach(l => l.classList.remove("active"));
        link.classList.add("active");
    });
});