function sprawdz()
{
	var poprawna   = document.forms["form2"]["poprawna1"].value;
	var zaznaczona = document.querySelector('input[name=odp1]:checked').value
	var elem       = document.getElementById("form2").elements;
		
	for(i = 0; i < elem.length; i++)
	{
		if(elem[i].type == "hidden")
		{			
			for(y = i + 1; y < i + 5; y++)
			{
				if(elem[y].value == elem[i].value)
				{
					elem[y].parentNode.style.background = "lightgreen";
				}
				else if(elem[y].value == zaznaczona)
				{
					elem[y].parentNode.style.background = "#ff3333";
				}
			}
		}
		if(elem[i].type == "radio")
		{
			elem[i].disabled = "disabled";
		}
	}
	
	console.log(zaznaczona + poprawna);
	
	if(zaznaczona == poprawna)
	{	
		//alert("Zaznaczyles dobra odpowiedz!" + zaznaczona + poprawna);
		document.getElementById("wynik").style.height = "45px";
		document.getElementById("wynik").innerHTML = "Poprawna odpowiedz!";
		document.getElementById("wynik").style.backgroundColor = "green";
	}
	if(zaznaczona != poprawna)
	{
		//alert("Zaznaczyles zla odpowiedz!!!" + zaznaczona + poprawna);
		document.getElementById("wynik").style.height = "45px";
		document.getElementById("wynik").innerHTML = "Zła odpowiedz!";
		document.getElementById("wynik").innerHTML += "<br>Poprawna odpowiedz to " + poprawna.toUpperCase();
		document.getElementById("wynik").style.backgroundColor = "red";
	}
}
 trwaTest = true;

function sprawdz40()
{
	trwaTest = false;
	var zaznaczone = [];
	var poprawne = [];
	var radia = [];
	var wynik = 0;
	var str;
	elem = document.getElementById("form2").elements;
	
	for( i = 0; i < 40; i++)
	{
		//document.querySelector('input[name=odp1]:checked').value
		//console.log(document.querySelector("input[name=odp" + i + "]:checked").value);
		try
		{
			zaznaczone.push(document.querySelector("input[name=odp" + i + "]:checked").value);
		}
		catch(err)
		{
			zaznaczone.push("undefined");
		}
	}		
	z = 0;
	for(i = 0; i < elem.length; i++)
	{
		if(elem[i].type == "hidden")
		{
			poprawne.push(elem[i]);
			
			for(y = i + 1; y < i + 5; y++)
			{
				if(elem[y].value == elem[i].value)
				{
					elem[y].parentNode.style.background = "lightgreen";
				}
				else if(elem[y].value == zaznaczone[z])
				{
					elem[y].parentNode.style.background = "#ff3333";
				}
			}
			z++;
			console.log(z);
		}
		if(elem[i].type == "radio")
		{
			elem[i].disabled = "disabled";
		}
	}
	document.getElementById("wynik").innerHTML = str;
	
	for(i = 0; i <= 39; i++)
	{
		if(zaznaczone[i] == poprawne[i].value)
		{
			wynik++;
		}
	}
	
	if(wynik >= 20)
	{
		document.getElementById("wynik").style.height = "45px";
		document.getElementById("wynik").innerHTML = "Zadane!<br>Punkty: " + wynik + "/40.";
		document.getElementById("wynik").style.backgroundColor = "green";
	}
	else
	{
		//alert("Zaznaczyles zla odpowiedz!!!" + zaznaczona + poprawna);
		document.getElementById("wynik").style.height = "45px";
		document.getElementById("wynik").innerHTML = "Niezdane!<br>Punkty: " + wynik + "/40.";
		document.getElementById("wynik").style.backgroundColor = "red";
	}
	
	scroll(0,0)
	console.log("Sprawdzanie");
	
}

$(document).ready(function()
{
	$('#form2').on('submit', function(e)
	{
		e.preventDefault();
		$.ajax(
		{
			url: $(this).attr('action') || window.location.pathname,
			type: "GET",
			data: $(this).serialize(),
			success: function(data)
			{
				//$("#wynik").html(data);
			},
			error: function(jXHR, textStatus, errorThrown)
			{
				alert(errorThrown);
			}
		});
	});
});

function wysPytania2()
{
	var myform = document.getElementById("form1");
	var fd = new FormData(myform);
	
	$.ajax
	({
		url: 'wysPytania.php',
		data: fd,
		cache: false,
		processData: false,
		//contentType: false,
		type: 'POST',
		success: function(response)
		{
			alert("dziala!");
			$('#strona').find('#tresc').html(response);
			$('form').unbind().submit();
		}
	})
	return false;
}

var startTime;

function getStartTime()
{
	var startData = new Date();
	startTime = startData.getTime();
	
}

min = 60;
sec = 0;

function minutnik2()
{
	var data = new Date();
	time = data.getTime();
	
	czaspo = parseInt((time - startTime)/1000);
	pozostalyCzas = 3600 - czaspo;
	
	minutes = Math.floor(pozostalyCzas / 60);
	seconds = pozostalyCzas - (minutes * 60);
	
	if(minutes < 10)
	{
		minutes = "0" + minutes;
	}
	if(seconds < 10)
	{
		seconds = "0" + seconds;
	}
	
	document.getElementById("czas").innerHTML = minutes + ":" + seconds;
	//document.getElementById("czas").innerHTML = czaspo + "/" +pozostalyCzas;
	if((time <= (startTime + 3600000)) && trwaTest)
	{
		var t = setTimeout(minutnik2, 1);
	}
	if(time >= (startTime + 3600000))
	{
		sprawdz40();
	}
}

function getIndex(element)
{
    for (var i=0; i<document.forms["form2"].elements.length; i++)
	{
		if (element == document.forms["form2"].elements[i])
		{
			return i;
		}
	}
}

function otworzOkno()
{
	document.getElementById("dodajpytanie").style.visibility = "visible";
} 

function zamknijOkno()
{
	document.getElementById("dodajpytanie").style.visibility = "hidden";
}