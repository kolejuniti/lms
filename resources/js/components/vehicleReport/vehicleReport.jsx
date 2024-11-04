import React from 'react';

class VehicleReport extends React.Component {

    componentDidMount() {
        // Initialize the DataTable
        const table = $(this.el).DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        });
    
        // Append buttons to the DataTable container and move to left
        table.buttons().container().appendTo('#vehicle_report_wrapper .col-md-6:eq(0)');
    
        // Add a custom class to align buttons to the left
        $('#vehicle_report_wrapper .col-md-6:eq(0)').addClass('text-left');
    }
    
    componentDidUpdate() {
        // Destroy and reinitialize the DataTable to handle data updates
        const table = $(this.el).DataTable();
        table.clear().rows.add(this.props.data).draw();
    }
    
    componentWillUnmount() {
        // Destroy the DataTable before unmounting
        $(this.el).DataTable().destroy(true);
    }
    
    renderTableData() {
        const { data } = this.props;
    
        return data.vehicle.map((vehicle, vehicleIndex) => (
            <tr key={`vehicle-${vehicleIndex}`}>
            <td>{vehicleIndex + 1}</td>
            <td>{vehicle.type}</td>
            <td>{vehicle.brand}</td>
            <td>{vehicle.model}</td>
            <td>{vehicle.colour}</td>
            <td>{vehicle.year}</td>
            <td>{new Date(vehicle.date_of_purchase).toLocaleDateString()}</td>
            <td>{vehicle.registration_number}</td>
            <td>{new Date(vehicle.date_of_roadtax).toLocaleDateString()}</td>
            <td>
                <table className="table table-bordered">
                <tbody>
                    {data.service[vehicleIndex].map((service, serviceIndex) => (
                    <tr key={`service-${vehicleIndex}-${serviceIndex}`}>
                        <td>Date : {new Date(service.date_of_service).toLocaleDateString()}</td>
                        <td>Odometer : {service.odometer}</td>
                        <td>Company : {service.company}</td>
                        <td>Amount : {service.amount}</td>
                        <td>
                        <table className="table table-bordered">
                            <tbody>
                            <tr>
                                <td colSpan={3}>Services</td>
                            </tr>
                            {data.details[vehicleIndex][serviceIndex].map((detail, detailIndex) => (
                                <tr key={`detail-${vehicleIndex}-${serviceIndex}-${detailIndex}`}>
                                <td>{detailIndex + 1}</td>
                                <td>{detail.type_of_services}</td>
                                <td>{detail.amount}</td>
                                </tr>
                            ))}
                            </tbody>
                        </table>
                        </td>
                    </tr>
                    ))}
                </tbody>
                </table>
            </td>
            <td>
                <table className="table table-bordered">
                <tbody>
                    {data.odometer[vehicleIndex].map((odometer, odometerIndex) => (
                    <tr key={`odometer-${vehicleIndex}-${odometerIndex}`}>
                        <td>{odometerIndex + 1}</td>
                        <td>{odometer.odometer}</td>
                        <td>{/* Other odometer data */}</td>
                    </tr>
                    ))}
                </tbody>
                </table>
            </td>
            </tr>
        ));
    }

    render() {
        return (
            <div>
                <table id="vehicle_report" className="table table-bordered" ref={el => this.el = el}>
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Type</th>
                            <th>Brand</th>
                            <th>Model</th>
                            <th>Colour</th>
                            <th>Year</th>
                            <th>Date Purchase</th>
                            <th>Registration No.</th>
                            <th>Date Roadtax</th>
                            <th>Details</th>
                            <th>Odometer</th>
                        </tr>
                    </thead>
                    <tbody>
                        {this.renderTableData()}
                    </tbody>
                </table>
            </div>
        );
    }
}

export default VehicleReport;