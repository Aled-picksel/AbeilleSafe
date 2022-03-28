// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

function number_format(number, decimals, dec_point, thousands_sep) {
  // *     example: number_format(1234.56, 2, ',', ' ');
  // *     return: '1 234,56'
  number = (number + '').replace(',', '').replace(' ', '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + Math.round(n * k) / k;
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1).join('0');
  }
  return s.join(dec);
}

var ctxW = document.getElementById("weiChart");
var ctxI = document.getElementById("intChart");
var ctxE = document.getElementById("extChart");
var ctxP = document.getElementById("atmChart");

var array_datetimes = ctxW.dataset.datetimes.slice(0, -1).split(",");
var array_weights = ctxW.dataset.weights.slice(0, -1).split(",").map(Number);
var array_otemp = ctxE.dataset.text.slice(0, -1).split(",").map(Number);
var array_itemp = ctxI.dataset.tint.slice(0, -1).split(",").map(Number);
var array_ohum = ctxE.dataset.hext.slice(0, -1).split(",").map(Number);
var array_ihum = ctxI.dataset.hint.slice(0, -1).split(",").map(Number);
var array_press = ctxP.dataset.patm.slice(0, -1).split(",").map(Number);




/*DEBUG*/ console.log(array_datetimes);
/*DEBUG*/ console.log(array_weights);

var wChart = new Chart(ctxW, {
  type: 'line',
  data: {
    labels: array_datetimes, //LE BAS
    datasets: [{
      label: "Masse",
      borderColor: 'rgb(255, 0, 0)',
      backgroundColor: 'rgb(255, 150, 150)',
      data: array_weights, //LES DONNEES
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
          left: 20,
          right: 10,
          top: 10,
          bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          parser: 'DD/MM/YYYY HH:mm',
          tooltipFormat: 'll HH:mm',
          unit: 'day',
          unitStepSize: 1,
          displayFormats: {
            'day': 'DD/MM/YYYY'
          }
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          autoSkip: true,
          reverse: true,
          maxRotation: 45,
          minRotation: 45
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          callback: function(value, index, values) {
            return number_format(value) + ' kg';
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: true
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ' : ' + number_format(tooltipItem.yLabel,4) + " kg";
        }
      }
    }
  }
});





var intChart = new Chart(ctxI, {
  type: 'line',
  data: {
    labels: array_datetimes, //LE BAS
    datasets: [{
      label: "T° int",
      borderColor: 'rgb(0, 0, 255)',
      backgroundColor: 'rgb(150, 150, 255)',
      data: array_itemp, //LES DONNEES
    }, {
      label: "H% int",
      borderColor: 'rgb(0, 255, 0)',
      data: array_ihum, //LES DONNEES
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
          left: 20,
          right: 10,
          top: 10,
          bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          parser: 'DD/MM/YYYY HH:mm',
          tooltipFormat: 'll HH:mm',
          unit: 'day',
          unitStepSize: 1,
          displayFormats: {
            'day': 'DD/MM/YYYY'
          }
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          autoSkip: true,
          reverse: true,
          maxRotation: 45,
          minRotation: 45
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          callback: function(value, index, values) {
            return number_format(value) + ' °C/%';
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: true
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          var suffix = "";
          switch(datasetLabel){
          case "H% int":
            suffix=" %";
            break;
          case "T° int":
            suffix=" °C";
            break;
          }
          return datasetLabel + ' : ' + number_format(tooltipItem.yLabel,1) + suffix;
        }
      }
    }
  }
});



var extChart = new Chart(ctxE, {
  type: 'line',
  data: {
    labels: array_datetimes, //LE BAS
    datasets: [{
      label: "T° ext",
      borderColor: 'rgb(0, 0, 255)',
      backgroundColor: 'rgb(150, 150, 255)',
      data: array_otemp, //LES DONNEES
    }, {
      label: "H% ext",
      borderColor: 'rgb(0, 255, 0)',
      data: array_ohum, //LES DONNEES
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
          left: 20,
          right: 10,
          top: 10,
          bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          parser: 'DD/MM/YYYY HH:mm',
          tooltipFormat: 'll HH:mm',
          unit: 'day',
          unitStepSize: 1,
          displayFormats: {
            'day': 'DD/MM/YYYY'
          }
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          autoSkip: true,
          reverse: true,
          maxRotation: 45,
          minRotation: 45
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          callback: function(value, index, values) {
            return number_format(value) + ' °C/%';
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: true
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          var suffix = "";
          switch(datasetLabel){
          case "H% ext":
            suffix=" %";
            break;
          case "T° ext":
            suffix=" °C";
            break;
          }
          return datasetLabel + ' : ' + number_format(tooltipItem.yLabel,1) + suffix;
        }
      }
    }
  }
});



var atmChart = new Chart(ctxP, {
  type: 'line',
  data: {
    labels: array_datetimes, //LE BAS
    datasets: [{
      label: "Pression atmosphérique",
      borderColor: 'rgb(255, 0, 255)',
      backgroundColor: 'rgb(255, 150, 255)',
      data: array_press, //LES DONNEES
    }],
  },
  options: {
    maintainAspectRatio: false,
    layout: {
      padding: {
          left: 20,
          right: 10,
          top: 10,
          bottom: 0
      }
    },
    scales: {
      xAxes: [{
        time: {
          parser: 'DD/MM/YYYY HH:mm',
          tooltipFormat: 'll HH:mm',
          unit: 'day',
          unitStepSize: 1,
          displayFormats: {
            'day': 'DD/MM/YYYY'
          }
        },
        gridLines: {
          display: false,
          drawBorder: false
        },
        ticks: {
          autoSkip: true,
          reverse: true,
          maxRotation: 45,
          minRotation: 45
        }
      }],
      yAxes: [{
        ticks: {
          maxTicksLimit: 5,
          callback: function(value, index, values) {
            return number_format(value) + ' hpa';
          }
        },
        gridLines: {
          color: "rgb(234, 236, 244)",
          zeroLineColor: "rgb(234, 236, 244)",
          drawBorder: false,
          borderDash: [2],
          zeroLineBorderDash: [2]
        }
      }],
    },
    legend: {
      display: true
    },
    tooltips: {
      backgroundColor: "rgb(255,255,255)",
      bodyFontColor: "#858796",
      titleMarginBottom: 10,
      titleFontColor: '#6e707e',
      titleFontSize: 14,
      borderColor: '#dddfeb',
      borderWidth: 1,
      xPadding: 15,
      yPadding: 15,
      displayColors: false,
      intersect: false,
      mode: 'index',
      caretPadding: 10,
      callbacks: {
        label: function(tooltipItem, chart) {
          var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
          return datasetLabel + ' : ' + number_format(tooltipItem.yLabel,1) + " hpa";
        }
      }
    }
  }
});