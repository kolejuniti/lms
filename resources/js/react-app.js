import React from 'react';
import ReactDOM from 'react-dom';
import SenateReport from './components/senateReport/senateReport.jsx';
import ResultReport from './components/resultReport/resultReport.jsx';
import VehicleReport from './components/vehicleReport/vehicleReport.jsx';
import AnnouncementSystem from './components/annoucementStudent/annoucementStudent.jsx';

window.renderSenateReport = function(data) {

    const container = document.createElement('div');
    container.id = 'senateReport';
    $('#form-student').html(container);

    ReactDOM.render(
        <SenateReport data={data} />,
        document.getElementById('senateReport')
    );

}

window.renderResultReport = function(data){

    const container = document.createElement('div');
    container.id = 'resultReport';
    $('#form-student').html(container);

    ReactDOM.render(
        <ResultReport data={data} />,
        document.getElementById('resultReport')
    )

}

window.renderVehicleReport = (data) => {

    const container = document.createElement('div');
    container.id = 'vehicleReport';
    $('#form-student').html(container);

    ReactDOM.render(
        <VehicleReport data={data} />,
        document.getElementById('vehicleReport')
    )

}

window.renderAnnouncementSystem = () => {

    const container = document.createElement('div');
    container.id = 'announcementSystem';
    $('#form-student').html(container);

    ReactDOM.render(
        <AnnouncementSystem />,
        document.getElementById('announcementSystem')
    )

}