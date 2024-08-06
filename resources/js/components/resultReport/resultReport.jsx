import React from 'react';

class ResultReport extends React.Component {
  componentDidMount() {
    // Initialize the DataTable
    const table = $(this.el).DataTable({
      "responsive": true,
      "lengthChange": false,
      "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    });

    // Append buttons to the DataTable container and move to left
    table.buttons().container().appendTo('#senate_report_wrapper .col-md-6:eq(0)');

    // Add a custom class to align buttons to the left
    $('#senate_report_wrapper .col-md-6:eq(0)').addClass('text-left');
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
    return this.props.data.map((item, index) => (
      <tr key={index}>
        <td>{index+1}</td>
        <td>{item.name}</td>
        <td>{item.ic}</td>
        <td>{item.progcode}</td>
        <td>{item.no_matric}</td>
        <td>{item.semester}</td>
        <td>{item.gpa}</td>
        <td>{item.cgpa}</td>
        <td>{item.status_name}</td>
        {/* Add more fields as needed */}
      </tr>
    ));
  }

  render() {
    return (
      <div>
        <table id="senate_report" className="table table-bordered" ref={el => this.el = el}>
          <thead>
            <tr>
              <th>No</th>
              <th>Name</th>
              <th>IC No.</th>
              <th>Program</th>
              <th>Matric No.</th>
              <th>Semester</th>
              <th>GPA</th>
              <th>CGPA</th>
              <th>Result</th>
              {/* Add more headers as needed */}
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

export default ResultReport;
