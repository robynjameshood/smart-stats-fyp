
let linesups = document.getElementsByClassName("lineups");

let statistics = document.getElementsByClassName("statistics");

let notificationValues = document.getElementsByClassName("notification-values");

let player_watch = document.getElementById("player-watch");

player_watch.addEventListener("click", openPlayerWatch);

for (let i = 0; i< linesups.length;i++ ) {
    let fixtureID = linesups[i].getAttribute('name');
    let homeTeam = linesups[i].getAttribute("homeTeam");
    let awayTeam = linesups[i].getAttribute("awayTeam");
   linesups[i].addEventListener("click", function () {
       openLineups(fixtureID, homeTeam, awayTeam);
   })
}

for (let i = 0; i < notificationValues.length;i++) {
    let home =  notificationValues[i].getAttribute("homeTeam");
    let away = notificationValues[i].getAttribute("awayTeam");
    let homeGoals = notificationValues[i].getAttribute("homeGoals");
    let awayGoals = notificationValues[i].getAttribute("awayGoals");
    let league = notificationValues[i].getAttribute("league");
    let time = notificationValues[i].getAttribute("time");
    let parsedHomeGoals = parseInt(homeGoals);
    let parsedAwayGoals = parseInt(awayGoals);
    let totalGoals = parsedHomeGoals + parsedAwayGoals;

    if (time > 70 && totalGoals <= 1) {
        console.log(totalGoals);
        notifyBrowser(home, away, homeGoals, awayGoals, time, league);
    }
}

for (let x = 0; x< statistics.length;x++ ) {
    let id = statistics[x].getAttribute("name");
    let home = statistics[x].getAttribute("home");
    let away = statistics[x].getAttribute("away");
    statistics[x].addEventListener("click", function () {
       openStatistics(id, home, away);
    });
}

function openPlayerWatch() {
    window.open("player-watch.php", "popup", "width=900px, height=900px");
}

function openLineups(fixtureID, homeTeam, awayTeam) {
    window.open("lineups.php?id="+fixtureID+"&homeTeam="+homeTeam+"&awayTeam="+awayTeam, "popup", "width=700px, height=700px");
}

function openStatistics(fixtureID, homeTeam, awayTeam) {
    window.open("statistics.php?id="+fixtureID+"&homeTeam="+homeTeam+"&awayTeam="+awayTeam, "popup", "width=1000px, height=800px");
}

function notifyBrowser(home, away, homeGoals, awayGoals, time, league) {
    if (!Notification) {
        console.log('Desktop notifications not available in your browser..');
    }
    if (Notification.permission !== "granted") {
        Notification.requestPermission();
    } else {
        let notification = new Notification(" \nLate Goal Notice:  " + " " + league, {
            icon: '../smart-stats/images/lategoal.png',
            body: "Time: " + time + "\n" + home + " " + homeGoals + " : " + awayGoals + " " + away,
        });
    }
}

