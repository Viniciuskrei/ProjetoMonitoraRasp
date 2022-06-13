function atualizaHora(){
	var exibir = document.getElementById('horario');
	
	var agora = new Date();
	var horario = corrigeNumero(agora.getHours()) + ':' + corrigeNumero(agora.getMinutes()) + ':' +
				  corrigeNumero(agora.getSeconds());

	exibir.textContent = 'Hora do Sistema: ' + horario;

	if(corrigeNumero(agora.getMinutes()) % 5 == 0){
		setInterval(atualizaPage, 60000)
	}
}

function corrigeNumero(numero){
	if(numero < 10){
		numero = '0' + numero;
	}

	return numero;
}

function atualizaPage(){
	document.location.reload();
}

setInterval(atualizaHora, 1000);