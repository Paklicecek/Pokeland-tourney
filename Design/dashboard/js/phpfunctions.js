document.querySelector(".start").addEventListener("click", function() {
    fetchAndUpdate("../API/Start.php", { action: "start" });
});

document.querySelector(".removeDB").addEventListener("click", function() {
    removeDBHandler("../PHP/removeDB.php", { action: "removeDB" });
});

document.querySelector(".matches").addEventListener("click", function() {
    fetchAndUpdate("../API/Generate.php", { action: "matches" });
});

document.querySelector(".reset").addEventListener("click", function() {
    fetchAndUpdate("../API/Reset.php", { action: "reset" });
});

document.querySelector(".remove").addEventListener("click", function() {
    fetchAndUpdate("../API/Remove.php", { action: "remove" });
});

document.querySelector(".shuffle").addEventListener("click", function() {
    fetchAndUpdate("../API/Shuffle.php", { action: "shuffle" });
});

document.querySelector(".submit-player").addEventListener("click", function(e) {
    e.preventDefault(); 
    
    const form = this.closest("form");
    const formData = new FormData(form);
    const winner = formData.get("winner");
    
    if (!winner) {
        alert("Prosím vyber hráče");
        return;
    }
    
    fetchAndUpdate2("winner.php", { winner: winner });
});

async function fetchAndUpdate(url, data) {
    try {
        const response = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams(data)
        });
        
        const contentType = response.headers.get("content-type");
        let result;
        
        if (contentType && contentType.includes("application/json")) {
            result = await response.json();
            const resultElement = document.getElementById("result");
            if (resultElement) {
                if (result.success) {
                    resultElement.innerHTML = `<p class='success'>${result.message}</p>`;
                } else {
                    resultElement.innerHTML = `<p class='error'>${result.message}</p>`;
                }
            }
        } else {
            result = await response.text();
            const resultElement = document.getElementById("result");
            if (resultElement) {
                resultElement.innerHTML = result;
            }
        }
        
        setTimeout(() => {
            location.reload();
        }, 2000);
    } catch (error) {
        console.error("Error:", error);
        const resultElement = document.getElementById("result");
        if (resultElement) {
            resultElement.innerHTML = "<p class='error'>Nastala chyba při zpracování požadavku</p>";
        }
    }
}
async function fetchAndUpdate2(url, data) {
    try {
        const response = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams(data)
        });
        
        const contentType = response.headers.get("content-type");
        let result;
        
        if (contentType && contentType.includes("application/json")) {
            result = await response.json();
            const resultElement = document.getElementById("result");
            if (resultElement) {
                if (result.success) {
                    resultElement.innerHTML = `<p class='success'>${result.message}</p>`;
                } else {
                    resultElement.innerHTML = `<p class='error'>${result.message}</p>`;
                }
            }
        } else {
            result = await response.text();
            const resultElement = document.getElementById("result");
            if (resultElement) {
                resultElement.innerHTML = result;
            }
        }
        

    } catch (error) {
        console.error("Error:", error);
    }
}
async function removeDBHandler() {
    try {
        const resultElement = document.getElementById("result");
        if (resultElement) {
            resultElement.innerHTML = "<p class='success'>Processing request...</p>";
        }
        
        const response = await fetch("RemoveDB.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: new URLSearchParams({ action: "removeDB" })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.text();
        console.log("RemoveDB Response:", result);
        
        if (resultElement) {
            resultElement.innerHTML = result;
        }
        
        setTimeout(() => {
            location.reload();
        }, 2000);
    } catch (error) {
        console.error("RemoveDB Error:", error);
        const resultElement = document.getElementById("result");
        if (resultElement) {
            resultElement.innerHTML = "<p class='error'>An error occurred: " + error.message + "</p>";
        }
    }
}