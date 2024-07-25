import React from 'react';

class SenateReport extends React.Component {
    componentDidMount() {
        // Initialize the DataTable
        $(this.el).DataTable({
          "responsive": true,
          "lengthChange": false,
          "autoWidth": false,
          "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#senateReport_wrapper .col-md-6:eq(0)');
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
        <td>{item.name}</td>
        <td>{item.student_ic}</td>
        <td>{item.total_credit_s}</td>
        <td>{item.grade_pointer_s}</td>
        <td>{item.gpa}</td>
        <td>{item.total_credit_c}</td>
        <td>{item.grade_pointer_c}</td>
        <td>{item.cgpa}</td>
        <td>{item.status}</td>
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
              <th>Nama Pelajar</th>
              <th>No. KP</th>
              <th>Credit Semester</th>
              <th>Grade Value Semester</th>
              <th>GPA</th>
              <th>Total Credit</th>
              <th>Total Grade Value</th>
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

export default SenateReport;
