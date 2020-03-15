let baseuri = 'https://covid19.geo-spatial.org/api/dashboard/';
// let baseuri = 'app/data/covid19geospatial/';
let endpoints = ['getCasesByCounty', 'getDeadCasesByCounty', 'getHealthCasesByCounty', 'getDailyCaseReport', 'getCaseRelations'];

function fetchJson(url){
  $.getJSON(url, function (json) {
    // response = $.parseJSON(json);
    // console.log(json); // this will show the info it in firebug console
    let statsDiv = $('<div id="statswrapper"></div>');
    let togglejud = $('<div id="togglejudete"><a href="#">&#x27e9;	lista pe județe</a></div>');
    let statsList = $('<ul id="statsList" class="list-unstyled"><li><b>C</b>azuri <br><b>Î</b>nsănătoșiri <br><b>D</b>ecese</li> <li><span class="code">jud</span><span class="total">C</span><span class="healed">Î</span><span class="dead">D</span></li></ul>');
    $("#main-wrapper").append(statsDiv);
    statsDiv.html('<div id="stats-total"><b>' + json.data.total + '</b> cazuri</div>')
    let jsondata = json.data.data;
    for (var i = 0, len = jsondata.length; i < len; i++) {
      let jsdata = jsondata[i];
      let xlist = $('<li></li>')
      xlist.append(row_CasesByCounty(jsdata));
      statsList.append(xlist);
    }
    
    statsDiv.append(statsList);
    
    statsDiv.append('<div class="stats-footer"> vezi <a href="https://covid19.geo-spatial.org/dashboard/main" target="_blank"><u>hartă</u></a></div>')
    statsDiv.append(togglejud);
    $('#togglejudete a').click(function () {
      $('#statswrapper').toggleClass('showdetails');
    });
  });
}


function row_CasesByCounty(jsdata){
  return (
    '<span class="code">' + jsdata.county_code + '</span>' +
    '<span class="total">' + jsdata.total_county + '</span>' +
    '<span class="healed">' + (jsdata.total_healed ? jsdata.total_healed : ' ') + '</span>' +
    '<span class="dead">' + (jsdata.total_dead ? jsdata.total_dead : ' ') + '</span>'
    ) ;
}

fetchJson(baseuri + 'getCasesByCounty');

// todo, try to get live json, if not, revert to local