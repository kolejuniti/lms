import React, { useState, useEffect } from 'react';
import axios from 'axios';
import { Editor } from '@tinymce/tinymce-react';


const AnnouncementManagement = () => {

  const element = document.getElementById('announcement-management');
  if (!element) return <div>Error: Announcement container not found.</div>;

  const userRole = element.dataset.userRole;
  let type;

  if (userRole === 'ADM') {
    type = 'Admin';
  } else if (userRole === 'FN') {
    type = 'Finance';
  } else if (userRole === 'AR') {
    type = 'Pendaftar Akademik';
  } else if (userRole === 'RGS') {
    type = 'Pendaftar';
  }


  const [announcements, setAnnouncements] = useState([]);
  const [showForm, setShowForm] = useState(false);
  const [showEditModal, setShowEditModal] = useState(false);
  const [newAnnouncement, setNewAnnouncement] = useState({
    title: '',
    content: '',
    start_date: '',
    end_date: '',
    department: type,
    priority: 'low',
  });
  const [editAnnouncement, setEditAnnouncement] = useState(null);
  const [isLoading, setIsLoading] = useState(false);
  const [errorMessage, setErrorMessage] = useState('');

  // Fetch announcements from backend
  useEffect(() => {
    const fetchAnnouncements = async () => {
      setIsLoading(true);
      try {
        const response = await axios.get('/all/student/announcements/getannoucement');
        setAnnouncements(response.data);
        setIsLoading(false);
      } catch (error) {
        console.error('Error fetching announcements:', error);
        setErrorMessage('Failed to load announcements.');
        setIsLoading(false);
      }
    };
    fetchAnnouncements();
  }, []);

  // Add a new announcement
  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      // alert(type);
      const response = await axios.post(`/all/student/announcements/post`, newAnnouncement);
      setAnnouncements([...announcements, { ...newAnnouncement, id: response.data.id }]);
      setNewAnnouncement({
        title: '',
        content: '',
        start_date: '',
        end_date: '',
        department: type,
        priority: 'low',
      });
      setShowForm(false);
      resetForm();
    } catch (error) {
      console.error('Error creating announcement:', error);
    }
  };

  // Update an existing announcement
  const handleUpdate = async (id, updatedData) => {
    setIsLoading(true);
    try {
      const response = await axios.put(`/all/student/announcements/put/${id}`, updatedData);

      setAnnouncements(
        announcements.map((announcement) =>
          announcement.id === id ? updatedData : announcement
        )
      );
      setShowEditModal(false);
      setIsLoading(false);
    } catch (error) {
      console.error('Error updating announcement:', error);
      setErrorMessage('Failed to update announcement.');
      setIsLoading(false);
    }
  };

  // Delete an announcement
  const handleDelete = async (id) => {
    if (window.confirm('Are you sure you want to delete this announcement?')) {
      setIsLoading(true);
      try {
        await axios.delete(`/all/student/announcements/delete/${id}`);
        setAnnouncements(announcements.filter((announcement) => announcement.id !== id));
        setIsLoading(false);
      } catch (error) {
        console.error('Error deleting announcement:', error);
        setErrorMessage('Failed to delete announcement.');
        setIsLoading(false);
      }
    }
  };

  const handleEditClick = (announcement) => {
    setEditAnnouncement(announcement);
    setShowEditModal(true);
  };

  const handleEditSubmit = (e) => {
    e.preventDefault();
    handleUpdate(editAnnouncement.id, editAnnouncement);
  };

  const handleModalClose = () => {
    setEditAnnouncement(null);
    setShowEditModal(false);
  };

  return (
    <div className="container py-4">
      <div className="mb-4">
        <div className="d-flex justify-content-between align-items-center mb-4">
          <h1 className="h3">Announcement Management</h1>
          {!showForm && (
            <button onClick={() => setShowForm(true)} className="btn btn-primary">
              Create Announcement
            </button>
          )}
        </div>

        {errorMessage && <div className="alert alert-danger">{errorMessage}</div>}

        {showForm && (
          <div className="card mb-4">
            <div className="card-body">
              <h2 className="h4 mb-3">New Announcement</h2>
              <form onSubmit={handleSubmit}>
                <div className="mb-3">
                  <label className="form-label">Title</label>
                  <input
                    type="text"
                    className="form-control"
                    value={newAnnouncement.title}
                    onChange={(e) =>
                      setNewAnnouncement({ ...newAnnouncement, title: e.target.value })
                    }
                    required
                  />
                </div>
                <div className="mb-3">
                  <label className="form-label">Content</label>
                  <Editor
                    apiKey='m87hnvtbh67hlojxi0rtvmck66pxl1t95e28zms4v8qhpn7v'
                    value={newAnnouncement.content}
                    init={{
                      plugins: [
                        // Core editing features
                        'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                        // Your account includes a free trial of TinyMCE premium features
                        // Try the most popular premium features until Feb 3, 2025:
                        'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
                      ],
                      toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                      tinycomments_mode: 'embedded',
                      tinycomments_author: 'Author name',
                      mergetags_list: [
                        { value: 'First.Name', title: 'First Name' },
                        { value: 'Email', title: 'Email' },
                      ],
                      ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
                    }}
                    onEditorChange={(content) =>
                      setNewAnnouncement({ ...newAnnouncement, content })
                    }
                  />
                </div>
                <div className="row mb-3">
                  <div className="col-md-6">
                    <label className="form-label">Start Date</label>
                    <input
                      type="date"
                      className="form-control"
                      value={newAnnouncement.start_date}
                      onChange={(e) =>
                        setNewAnnouncement({ ...newAnnouncement, start_date: e.target.value })
                      }
                      required
                    />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">End Date</label>
                    <input
                      type="date"
                      className="form-control"
                      value={newAnnouncement.end_date}
                      onChange={(e) =>
                        setNewAnnouncement({ ...newAnnouncement, end_date: e.target.value })
                      }
                      required
                    />
                  </div>
                </div>
                <div className="row mb-3">
                  <div className="col-md-6">
                    <label className="form-label">Department</label>
                    <select
                      className="form-select"
                      value={newAnnouncement.department}
                      onChange={(e) =>
                        setNewAnnouncement({ ...newAnnouncement, department: e.target.value })
                      }
                      disabled // This makes the select field read-only
                    >
                      <option value="Admin">Admin</option>
                      <option value="Finance">Finance</option>
                      <option value="Pendaftar Akademik">Pendaftar Akademik</option>
                      <option value="Pendaftar">Pendaftar</option>
                    </select>
                  </div>

                  <div className="col-md-6">
                    <label className="form-label">Priority</label>
                    <select
                      className="form-select"
                      value={newAnnouncement.priority}
                      onChange={(e) =>
                        setNewAnnouncement({ ...newAnnouncement, priority: e.target.value })
                      }
                    >
                      <option value="low">Low</option>
                      <option value="medium">Medium</option>
                      <option value="high">High</option>
                    </select>
                  </div>
                </div>
                <button type="submit" className="btn btn-primary">
                  Publish Announcement
                </button>
              </form>
            </div>
          </div>
        )}
      </div>

      {isLoading && <div className="text-center">Loading...</div>}

      <div className="row g-4">
        {announcements.length > 0 ? (
          announcements.map((announcement) => (
            <div key={announcement.id} className="col-12">
              <div
                className={`card border-${announcement.priority === 'high'
                    ? 'danger'
                    : announcement.priority === 'medium'
                      ? 'warning'
                      : 'success'
                  }`}
              >
                <div className="card-body">
                  <div className="d-flex justify-content-between align-items-start mb-3">
                    <div>
                      <h3 className="card-title h5 mb-2">{announcement.title}</h3>
                      <span className="badge bg-secondary">{announcement.department}</span>
                    </div>
                    <div>
                      <button
                        onClick={() => handleEditClick(announcement)}
                        className="btn btn-sm btn-warning me-2"
                      >
                        Edit
                      </button>
                      <button
                        onClick={() => handleDelete(announcement.id)}
                        className="btn btn-sm btn-danger"
                      >
                        Delete
                      </button>
                    </div>
                  </div>
                  <p
                    className="card-text"
                    dangerouslySetInnerHTML={{ __html: announcement.content }}
                  ></p>
                  <div className="d-flex align-items-center text-muted small">
                    <i className="bi bi-calendar me-2"></i>
                    <span className="me-3">
                      {announcement.start_date} - {announcement.end_date}
                    </span>
                  </div>
                </div>
              </div>
            </div>
          ))
        ) : (
          <p>No announcements to display.</p>
        )}
      </div>

      {showEditModal && editAnnouncement && (
        <div className="modal show d-block" tabIndex="-1" aria-labelledby="editAnnouncementModal">
          <div className="modal-dialog">
            <div className="modal-content">
              <div className="modal-header">
                <h5 className="modal-title">Edit Announcement</h5>
                <button
                  type="button"
                  className="btn-close"
                  onClick={handleModalClose} // Close the modal when clicked
                  aria-label="Close"
                ></button>
              </div>
              <div className="modal-body">
                <form onSubmit={handleEditSubmit}>
                  <div className="mb-3">
                    <label className="form-label">Title</label>
                    <input
                      type="text"
                      className="form-control"
                      value={editAnnouncement.title}
                      onChange={(e) =>
                        setEditAnnouncement({ ...editAnnouncement, title: e.target.value })
                      }
                      required
                    />
                  </div>
                  <div className="mb-3">
                    <label className="form-label">Content</label>
                    <Editor
                      apiKey='m87hnvtbh67hlojxi0rtvmck66pxl1t95e28zms4v8qhpn7v'
                      value={editAnnouncement.content}
                      init={{
                        plugins: [
                          // Core editing features
                          'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'image', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
                          // Your account includes a free trial of TinyMCE premium features
                          // Try the most popular premium features until Feb 3, 2025:
                          'checklist', 'mediaembed', 'casechange', 'export', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'editimage', 'advtemplate', 'ai', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
                        ],
                        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                        tinycomments_mode: 'embedded',
                        tinycomments_author: 'Author name',
                        mergetags_list: [
                          { value: 'First.Name', title: 'First Name' },
                          { value: 'Email', title: 'Email' },
                        ],
                        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
                      }}
                      onEditorChange={(content) =>
                        setEditAnnouncement({ ...editAnnouncement, content })
                      }
                    />
                  </div>
                  <div className="row mb-3">
                    <div className="col-md-6">
                      <label className="form-label">Start Date</label>
                      <input
                        type="date"
                        className="form-control"
                        value={editAnnouncement.start_date}
                        onChange={(e) =>
                          setEditAnnouncement({ ...editAnnouncement, start_date: e.target.value })
                        }
                        required
                      />
                    </div>
                    <div className="col-md-6">
                      <label className="form-label">End Date</label>
                      <input
                        type="date"
                        className="form-control"
                        value={editAnnouncement.end_date}
                        onChange={(e) =>
                          setEditAnnouncement({ ...editAnnouncement, end_date: e.target.value })
                        }
                        required
                      />
                    </div>
                  </div>
                  <button type="submit" className="btn btn-primary">
                    Save Changes
                  </button>
                  <button
                    type="button"
                    className="btn btn-secondary ms-2 pull-right"
                    onClick={handleModalClose} // Close the modal with a secondary button
                  >
                    Cancel
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      )}

    </div>
  );
};

export default AnnouncementManagement;
