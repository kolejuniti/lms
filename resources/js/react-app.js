import React from 'react';
import ReactDOM from 'react-dom';
import SenateReport from './components/senateReport/senateReport.jsx';

window.renderSenateReport = function(data) {

    const container = document.createElement('div');
    container.id = 'senateReport';
    $('#form-student').html(container);

    ReactDOM.render(
        <SenateReport data={data} />,
        document.getElementById('senateReport')
    );

}