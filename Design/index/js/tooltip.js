let tooltipCon = document.querySelector(".tooltip-container")
let tooltip = document.querySelector(".tooltip")

function showToolTip(){
    tooltip.style.display = "block"
}
function hideToolTip(){
    tooltip.style.display = "none"
}

tooltipCon.addEventListener("mouseover",showToolTip)
tooltipCon.addEventListener("mouseleave",hideToolTip)