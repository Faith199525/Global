let selected = document.getElementById("file-input");
let imageContainer = document.getElementById("images");
let numOfFiles = document.getElementById("num-of-files");

function preview() {
    
    imageContainer.innerHTML = "";
    // VALIDATE OR CHECK IF ANY FILE IS SELECTED.
    if (selected.files.length > 0) {

        // THE TOTAL FILE COUNT.
        numOfFiles.innerHTML =
            'Total Number of Files Selected: <b>' + selected.files.length + '</b></br >';
    
    }

    for (i of selected.files) {
        let reader = new FileReader();
        let figure = document.createElement('figure');
        let figCap = document.createElement('figCaption');
        figCap.innerText = i.name;
        figure.appendChild(figCap);
        reader.onload=()=>{
            let img = document.createElement('img');
            img.setAttribute("src", reader.result);
            figure.insertBefore(img, figCap);
        }
        imageContainer.appendChild(figure);
        reader.readAsDataURL(i);
    }
    
}