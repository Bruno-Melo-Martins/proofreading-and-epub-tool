// Recarregar iframe ao carregar a página
document.getElementsByTagName("visor").contentWindow.location.reload();

function visualizarEle(id1, id2){
    // Elemento que será mostrado
    const mostrar = document.getElementById("editor"+id1);
    const esconder = document.getElementById("editor"+id2);

    if (mostrar.hasAttribute("hidden")) {
        mostrar.removeAttribute("hidden");
    }

    if (!esconder.hasAttribute("hidden")) {
        esconder.setAttribute("hidden", "");
    }
}

// Para tab funcionar bem dentros dos textareas
document.getElementById('editorhtml').addEventListener('keydown', function(e) {
    if (e.key == 'Tab') {
      e.preventDefault();
      var start = this.selectionStart;
      var end = this.selectionEnd;
  
      // set textarea value to: text before caret + tab + text after caret
      this.value = this.value.substring(0, start) +
        "\t" + this.value.substring(end);
  
      // put caret at right position again
      this.selectionStart =
        this.selectionEnd = start + 1;
    }
  });
document.getElementById('editorcss').addEventListener('keydown', function(e) {
    if (e.key == 'Tab') {
      e.preventDefault();
      var start = this.selectionStart;
      var end = this.selectionEnd;
  
      // set textarea value to: text before caret + tab + text after caret
      this.value = this.value.substring(0, start) +
        "\t" + this.value.substring(end);
  
      // put caret at right position again
      this.selectionStart =
        this.selectionEnd = start + 1;
    }
});


// Ajustar escala do iframe
function ajustarZoom(valor, id){
    const iframe = document.getElementById(id);
    let z = parseFloat(iframe.currentCSSZoom);

    if (valor == 1) {
        z = z + 0.1;
    }else{
        z = z - 0.1;
    }
    
    iframe.style.zoom = z;

}
