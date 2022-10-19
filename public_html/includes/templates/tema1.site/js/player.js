const DEMO = false;
var streamurl = $("#streamurlPlayer3").val();
var name = $("#namePlayer3").val();
// Naziv Radija
const URL_STREAMING = streamurl;
const NOME_RADIO = name;

// Visite https://api.vagalume.com.br/docs/ para saber como conseguir uma chave para API de letras
var API_KEY = "18fe07917957c289983464588aabddfb";

window.onload = function() {
    var pagina = new Pagina;
    pagina.alterarTitle();
    pagina.setVolume();

    var player = new Player();
    player.play();

    setInterval(function() {
        pegarDadosStreaming();
    }, 4000);

    var capaAlbum = document.getElementsByClassName('capa-album')[0];
    capaAlbum.style.height = capaAlbum.offsetWidth + 'px';
}

// Controle do DOM
function Pagina() {
    // Alterar o título da página para o nome da rádio
    this.alterarTitle = function(titulo = NOME_RADIO) {
        document.title = titulo;
    };

    // Atualizar faixa atual
    this.atualizarFaixaAtual = function(musica, artista) {
        var faixaAtual = document.getElementById('faixaAtual');
        var artistaAtual = document.getElementById('artistaAtual');
        document.getElementById('topHeaderartist').innerHTML = artista;
        document.getElementById('topHeadersong').innerHTML = musica;
        if (musica !== faixaAtual.innerHTML) {
            // Caso a faixa seja diferente da atual, atualizar e inserir a classe com animação em css
            faixaAtual.className = 'animated flipInY text-uppercase';
            faixaAtual.innerHTML = musica;

            artistaAtual.className = 'animated flipInY text-capitalize';
            artistaAtual.innerHTML = artista;

            // // Atualizar o título do modal com a letra da música
            // document.getElementById('faixaAtual').innerHTML = musica + ' - ' + artista;
            // Removendo as classes de animação
            setTimeout(function() {
                faixaAtual.className = 'text-uppercase';
                artistaAtual.className = 'text-capitalize';
            }, 2000);
        }
    }
    

    // Ažurirajte sliku naslovnice programa Player i Background
    this.atualizarCapa = function(musica, artista) {
        // Imagem padrão caso não encontre nenhuma na API do iTunes
        var urlCapa = window._env.template.url + 'images/autodj.png';

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            // Seletores de onde alterar a imagem de capa do album
            var capaMusica = document.getElementById('capaAtual');
         //   var capaBackground = document.getElementById('capaBg');

            // Buscar imagem da capa na API do iTunes
            if (this.readyState === 4 && this.status === 200) {
                var dados = JSON.parse(this.responseText);
                var artworkUrl100 = (dados.resultCount) ? dados.results[0].artworkUrl100 : urlCapa;
                // Se retornar algum dado, alterar a resolução da imagem ou definir a padrão
                urlCapa = (artworkUrl100 != urlCapa) ? artworkUrl100.replace('100x100bb', '512x512bb') : urlCapa;

                capaMusica.style.backgroundImage = 'url(' + urlCapa + ')';
              //  capaBackground.style.backgroundImage = 'url(' + urlCapa + ')';

                if ('mediaSession' in navigator) {
                    navigator.mediaSession.metadata = new MediaMetadata({
                        title: musica,
                        artist: artista,
                        artwork: [{
                            src: urlCapa,
                            sizes: '512x512',
                            type: 'image/png'
                        }, ]
                    });
                }
            }
        }
        xhttp.open('GET', 'https://itunes.apple.com/search?term=' + musica + ' ' + artista + '&limit=1', true);
        xhttp.send();
    }

    // Mijenja procenat indikatora jačine zvuka
    this.alterarPorcentagemVolume = function(volume) {
        document.getElementById('indicadorVol').innerHTML = volume;

        if (typeof(Storage) !== 'undefined') {
            localStorage.setItem('volume', volume);
        }
    }

    // Configurira glasnoću ako je već promijenjena
    this.setVolume = function() {
        if (typeof(Storage) !== 'undefined') {
            var volumeLocalStorage = (localStorage.getItem('volume') === null) ? 80 : localStorage.getItem('volume');
            document.getElementById('volume').value = volumeLocalStorage;
            document.getElementById('indicadorVol').innerHTML = volumeLocalStorage;
        }
    }
    // Ažurira prikaz teksta

}

var audio = new Audio(URL_STREAMING + '/;');

// Controla Playera
function Player() {
    this.play = function() {
        // audio.play();
        var volumePadrao = document.getElementById('volume').value;
        if (typeof(Storage) !== 'undefined') {
            if (localStorage.getItem('volume') !== null) {
                audio.volume = intToDecimal(localStorage.getItem('volume'));
            } else {
                audio.volume = intToDecimal(volumePadrao);
            }
        } else {
            audio.volume = intToDecimal(volumePadrao);
        }
        document.getElementById('indicadorVol').innerHTML = volumePadrao;

        // audio.onabort = function() {
        //     audio.load();
        //     audio.play();
        // }
    };

    this.pause = function() {
        audio.pause();
    };
}

// Promjena tipke kad je zvuk zaustavljen
audio.onplay = function() {
    var botao = document.getElementById('botaoPlayer');
    
    
    if (botao.className === 'fa fa-play') {
        botao.className = 'fa fa-pause';
        document.getElementById('topHeaderButton').src = window._env.template.url + 'images/pause.png';
    }
}

// Promjena tipke kad je zvuk pusti
audio.onpause = function() {
    var botao = document.getElementById('botaoPlayer');

    if (botao.className === 'fa fa-pause') {
        botao.className = 'fa fa-play';
        document.getElementById('topHeaderButton').src = window._env.template.url + 'images/top_banner2_img.png';
    }
}



// Mute Button
audio.onvolumechange = function() {
    if (audio.volume > 0) {
        audio.muted = false;
    }
}

// Ako izgubite vezu sa serverom za streaming, prikažite ovo upozorenje
// audio.onerror = function() {
//     var confirmacao = confirm('Trenutno imate problema sa serverom ili konekcijom javite se na email xmexpi@gmail.com.');

//     if (confirmacao) {
//         window.location.reload();
//     }
// }

// Pomjeranje trake za zvuk
document.getElementById('volume').oninput = function() {
    audio.volume = intToDecimal(this.value);

    var pagina = new Pagina();
    pagina.alterarPorcentagemVolume(this.value);
}

// Play and Pusse button
function togglePlay() {
    if (!audio.paused) {
        audio.pause();
    } else {
        audio.load();
        audio.play();
    }
}

// Funkcija za isključivanje i uključivanje zvuka uređaja
function mutar() {
    if (!audio.muted) {
        document.getElementById('indicadorVol').innerHTML = 0;
        document.getElementById('volume').value = 0;
        audio.volume = 0;
        audio.muted = true;
    } else {
        var localVolume = localStorage.getItem('volume');
        document.getElementById('indicadorVol').innerHTML = localVolume;
        document.getElementById('volume').value = localVolume;
        console.log(localVolume);
        audio.volume = intToDecimal(localVolume);
        audio.muted = false;
    }
}

// Dohvaćanje podataka za streaming iz streaminga
function pegarDadosStreaming() {
    var xhttp = new XMLHttpRequest();
    var urlRequest = 'songname?name=1&';
    xhttp.onreadystatechange = function() {
        if (this.readyState === 4 && this.status === 200) {
            var dados = JSON.parse(this.responseText);
            var pagina = new Pagina();
            var musicaAtual = dados.faixa.replace('&amp;', '\'');
            musicaAtual = musicaAtual.replace('&amp;', '&');
            var artistaAtual = dados.artista.replace('&apos;', '\'');
            artistaAtual = artistaAtual.replace('&amp;', '&');
            // Promjena naslova stranice sa trenutnom pjesmom i izvođačem
            document.title = musicaAtual + ' - ' + artistaAtual + ' | ' + NOME_RADIO;
            if (document.getElementById('faixaAtual').innerHTML !== musicaAtual) {
                pagina.atualizarCapa(musicaAtual, artistaAtual);
                pagina.atualizarFaixaAtual(musicaAtual, artistaAtual);
              //  pagina.atualizarLetra(musicaAtual, artistaAtual);
            }
        }
    };
    xhttp.open('GET', urlRequest + 'url=' + streamurl, true);
    xhttp.send();
}
// Upravljanje uređajem pomoću tastera
document.addEventListener('keydown', function(k) {
    var k = k || window.event;
    var tecla = k.keyCode || k.which;
    var slideVolume = document.getElementById('volume');
    var pagina = new Pagina();
    switch (tecla) {
        case 32:
            togglePlay();
            break;
        case 80:
            togglePlay();
            break;
        case 77:
            mutar();
            break;
        case 48:
            audio.volume = 0;
            slideVolume.value = 0;
            pagina.alterarPorcentagemVolume(0);
            break;
        case 96:
            audio.volume = 0;
            slideVolume.value = 0;
            pagina.alterarPorcentagemVolume(0);
            break;
        case 49:
            audio.volume = 0.1;
            slideVolume.value = 10;
            pagina.alterarPorcentagemVolume(10);
            break;
        case 97:
            audio.volume = 0.1;
            slideVolume.value = 10;
            pagina.alterarPorcentagemVolume(10);
            break;
        case 50:
            audio.volume = 0.2;
            slideVolume.value = 20;
            pagina.alterarPorcentagemVolume(20);
            break;
        case 98:
            audio.volume = 0.2;
            slideVolume.value = 20;
            pagina.alterarPorcentagemVolume(20);
            break;
        case 51:
            audio.volume = 0.3;
            slideVolume.value = 30;
            pagina.alterarPorcentagemVolume(30);
            break;
        case 99:
            audio.volume = 0.3;
            slideVolume.value = 30;
            pagina.alterarPorcentagemVolume(30);
            break;
        case 52:
            audio.volume = 0.4;
            slideVolume.value = 40;
            pagina.alterarPorcentagemVolume(40);
            break;
        case 100:
            audio.volume = 0.4;
            slideVolume.value = 40;
            pagina.alterarPorcentagemVolume(40);
            break;
        case 53:
            audio.volume = 0.5;
            slideVolume.value = 50;
            pagina.alterarPorcentagemVolume(50);
            break;
        case 101:
            audio.volume = 0.5;
            slideVolume.value = 50;
            pagina.alterarPorcentagemVolume(50);
            break;
        case 54:
            audio.volume = 0.6;
            slideVolume.value = 60;
            pagina.alterarPorcentagemVolume(60);
            break;
        case 102:
            audio.volume = 0.6;
            slideVolume.value = 60;
            pagina.alterarPorcentagemVolume(60);
            break;
        case 55:
            audio.volume = 0.7;
            slideVolume.value = 70;
            pagina.alterarPorcentagemVolume(70);
            break;
        case 103:
            audio.volume = 0.7;
            slideVolume.value = 70;
            pagina.alterarPorcentagemVolume(70);
            break;
        case 56:
            audio.volume = 0.8;
            slideVolume.value = 80;
            pagina.alterarPorcentagemVolume(80);
            break;
        case 104:
            audio.volume = 0.8;
            slideVolume.value = 80;
            pagina.alterarPorcentagemVolume(80);
            break;
        case 57:
            audio.volume = 0.9;
            slideVolume.value = 90;
            pagina.alterarPorcentagemVolume(90);
            break;
        case 105:
            audio.volume = 0.9;
            slideVolume.value = 90;
            pagina.alterarPorcentagemVolume(90);
            break;
    }
});

// Pretvori cijelu vrijednost u decimalnu
function intToDecimal(vol) {
    var tamanhoStr = vol.length;
    if (tamanhoStr > 0 && tamanhoStr < 3) {
        if (tamanhoStr === 1) {
            volume = '0.0' + vol;
        } else {
            volume = '0.' + vol;
        }
    } else if (vol === '100') {
        volume = 1;
    }
    return volume;
}