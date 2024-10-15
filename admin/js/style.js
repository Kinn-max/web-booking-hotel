var menu_ts = document.querySelectorAll('.category_list-item.menu_ts');

menu_ts.forEach((menuItem) => {
    var dentail = menuItem.querySelector('.list-dentail');
    var round_round = menuItem.querySelector('.round-round');

    menuItem.addEventListener('click', () => {
        var all_none = document.querySelectorAll('.list-dentail');
        var transform_none = document.querySelectorAll('.round-round')
        for (var i = 0; i < all_none.length; i++) {
            if (all_none[i] !== dentail) {
                all_none[i].classList.remove('toggle-auto');
                transform_none[i].classList.remove('transform-deg');
            }
        }
        
        dentail.classList.toggle('toggle-auto');
        round_round.classList.toggle('transform-deg');
    });
});

    const areaFile = document.getElementById("area-file")
    const fileName = document.getElementById("file-name")
    const imgView = document.getElementById("img-view")

fileName.addEventListener("change",showImg)
function showImg(){
    var imgLink = URL.createObjectURL(fileName.files[0])
    imgView.style.backgroundImage = `url(${imgLink})`
    imgView.textContent = ""
    imgView.style.border = 0
}
areaFile.addEventListener("dragover",(e)=>{
    e.preventDefault()
})
areaFile.addEventListener("drop",(e)=>{
    e.preventDefault()
    fileName.files = e.dataTransfer.files
    showImg()
})