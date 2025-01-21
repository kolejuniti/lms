import React, { useState, useEffect } from 'react';
import axios from 'axios';

const AnnouncementBanner = () => {
  const [announcements, setAnnouncements] = useState([]);
  const [currentIndex, setCurrentIndex] = useState(0);

  useEffect(() => {
    const fetchAnnouncements = async () => {
      try {
        const response = await axios.get('all/student/announcements/getBannerAnnouncement');
        setAnnouncements(response.data);
      } catch (error) {
        console.error('Error fetching announcements:', error);
      }
    };

    fetchAnnouncements();
  }, []);

  const nextAnnouncement = () => {
    setCurrentIndex((prev) => (prev + 1) % announcements.length);
  };

  const prevAnnouncement = () => {
    setCurrentIndex((prev) => (prev - 1 + announcements.length) % announcements.length);
  };

  const getPriorityStyles = (priority) => {
    switch (priority) {
      case 'high':
        return {
          backgroundColor: '#ffe5e5',
          color: '#d32f2f',
          borderColor: '#f44336',
        };
      case 'medium':
        return {
          backgroundColor: '#fff7e5',
          color: '#f57c00',
          borderColor: '#ff9800',
        };
      case 'low':
        return {
          backgroundColor: '#e5f7ff',
          color: '#0288d1',
          borderColor: '#03a9f4',
        };
      default:
        return {
          backgroundColor: '#f9f9f9',
          color: '#757575',
          borderColor: '#bdbdbd',
        };
    }
  };

  if (announcements.length === 0) {
    return <p>Loading announcements...</p>;
  }

  return (
    <div
      style={{
        marginTop: '24px',
        marginBottom: '24px',
        backgroundColor: 'white',
        borderRadius: '12px',
        boxShadow: '0 4px 12px rgba(0, 0, 0, 0.1)',
        overflow: 'hidden',
      }}
    >
      <div
        style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          padding: '16px',
          backgroundColor: '#f4f4f4',
          borderBottom: '1px solid #e0e0e0',
        }}
      >
        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
          <span style={{ fontSize: '20px' }}>📢</span>
          <h3 style={{ margin: 0, fontWeight: '600', fontSize: '18px', color: '#333' }}>
            Announcements
          </h3>
        </div>
        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
          <button
            onClick={prevAnnouncement}
            style={{
              padding: '8px',
              backgroundColor: '#e0e0e0',
              borderRadius: '50%',
              border: 'none',
              cursor: 'pointer',
              transition: 'all 0.3s ease',
            }}
            aria-label="Previous announcement"
          >
            ←
          </button>
          <span style={{ fontSize: '14px', color: '#555' }}>
            {currentIndex + 1} / {announcements.length}
          </span>
          <button
            onClick={nextAnnouncement}
            style={{
              padding: '8px',
              backgroundColor: '#e0e0e0',
              borderRadius: '50%',
              border: 'none',
              cursor: 'pointer',
              transition: 'all 0.3s ease',
            }}
            aria-label="Next announcement"
          >
            →
          </button>
        </div>
      </div>

      <div
        style={{
            margin: '16px',
            padding: '16px',
            borderRadius: '8px',
            border: '1px solid',
            ...getPriorityStyles(announcements[currentIndex].priority),
        }}
        >
        <h4
            style={{
            fontWeight: '600',
            fontSize: '16px',
            marginBottom: '8px',
            }}
        >
            {announcements[currentIndex].title}
        </h4>
        
        {/* Department Badge */}
        <span
            style={{
            display: 'inline-block',
            backgroundColor: '#e0f7fa',
            color: '#00796b',
            fontSize: '12px',
            fontWeight: '500',
            borderRadius: '12px',
            padding: '4px 8px',
            marginBottom: '12px',
            }}
        >
            {announcements[currentIndex].department}
        </span>
        
        <div
            style={{
            fontSize: '14px',
            color: '#555',
            marginBottom: '12px',
            }}
            dangerouslySetInnerHTML={{
            __html: announcements[currentIndex].content,
            }}
        />
        <p
            style={{
            fontSize: '12px',
            color: '#888',
            }}
        >
            Posted: {new Date(announcements[currentIndex].created_at).toLocaleDateString()}
        </p>
      </div>

    </div>
  );
};

export default AnnouncementBanner;
