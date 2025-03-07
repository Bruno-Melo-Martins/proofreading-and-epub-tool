function NovoMetadado(botao){
    var span = document.getElementById("n-meta");
    var checkN =document.getElementById("check-n");
    if(span.hasAttribute("hidden")){
        span.removeAttribute("hidden");
        botao.innerHTML = "Excluir novo Metadado";
        checkN.checked = true;
    }else{
        span.setAttribute("hidden", "");
        botao.innerHTML = "Novo Metadado";
        checkN.checked = false;
    }
}
function visualizarImg(img){
    var vignetta = document.getElementById("vignetta");
    var ad = document.getElementById("ad");
    var imagem = img.cloneNode(true);
    imagem.setAttribute("id", "ampliada");

    vignetta.removeAttribute("hidden");
    ad.appendChild(imagem);

    document.body.style.overflow = "hidden";
}

function desVisualizarImg(){
    var vignetta = document.getElementById("vignetta");
    var imagem = document.getElementById("ampliada");
    
    imagem.remove();
    vignetta.setAttribute("hidden", "");

    document.body.style.overflow = "scroll";
}
function VisualizarIMG(acao){
    var salvar = document.getElementById("salvarimgs");
    var excluir = document.getElementById("excluirimgs");
    if(acao == 1){
        if(salvar.hasAttribute("hidden")){
            salvar.removeAttribute("hidden");
        }
        if(!excluir.hasAttribute("hidden")){
            excluir.setAttribute("hidden", "");
        }
    }else{
        if(excluir.hasAttribute("hidden")){
            excluir.removeAttribute("hidden");
        }
        if(!salvar.hasAttribute("hidden")){
            salvar.setAttribute("hidden", "");
        }
    }
}

function mudarMiniatura(select){
    var titulo = document.getElementById("titulo").innerHTML;
    var miniatura = document.getElementById("miniatura");
    
    miniatura.setAttribute("src", 'projetos/'+titulo+'/ebook/images/'+select.value);
}