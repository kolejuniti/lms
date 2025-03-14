import React, { useState, useEffect } from 'react';
import axios from 'axios';

const AnnouncementBanner = () => {
  // State to store announcements fetched from the backend
  const [announcements, setAnnouncements] = useState([]);
  
  // State to track which announcement is currently displayed
  const [currentIndex, setCurrentIndex] = useState(0);
  
  // State for animation
  const [animationDirection, setAnimationDirection] = useState('');

  // useEffect runs when the component is mounted to fetch announcements
  useEffect(() => {
    const fetchAnnouncements = async () => {
      try {
        // Axios GET request to fetch announcements from the backend API
        const response = await axios.get('all/student/announcements/getBannerAnnouncement');
        
        // Ensure we have data before setting it
        if (response.data && response.data.length > 0) {
          setAnnouncements(response.data);
        } else {
          // Fallback data in case API returns empty
          setAnnouncements([{
            title: "Welcome to UCMS!",
            department: "Administration",
            content: "Welcome to the University Content Management System. Check back for important announcements.",
            priority: "medium",
            created_at: new Date().toISOString()
          }]);
        }
      } catch (error) {
        console.error('Error fetching announcements:', error);
        // Set fallback announcement when API fails
        setAnnouncements([{
          title: "Welcome to UCMS!",
          department: "Administration",
          content: "Welcome to the University Content Management System. Check back for important announcements.",
          priority: "medium",
          created_at: new Date().toISOString()
        }]);
      }
    };

    fetchAnnouncements();
    
    // Auto-rotate announcements every 8 seconds, but only if we have more than one
    let rotationInterval;
    
    // Reference to the nextAnnouncement function for the interval
    const autoRotate = () => {
      if (announcements.length > 1 && !animationDirection) {
        nextAnnouncement();
      }
    };
    
    // Only set interval if we have multiple announcements
    if (announcements.length > 1) {
      rotationInterval = setInterval(autoRotate, 20000);
    }
    
    // Clear interval on component unmount
    return () => {
      if (rotationInterval) {
        clearInterval(rotationInterval);
      }
    };
  }, [announcements.length, animationDirection]);

  // Function to navigate to the next announcement with animation
  const nextAnnouncement = () => {
    if (animationDirection) return; // Prevent multiple animations at once
    
    setAnimationDirection('slide-out-left');
    
    // Short delay to allow animation to complete before changing content
    setTimeout(() => {
      setCurrentIndex((prev) => (prev + 1) % announcements.length);
      // Set the incoming animation after content change
      setAnimationDirection('slide-in-right');
      
      // Clear animation class after it completes
      setTimeout(() => {
        setAnimationDirection('');
      }, 300);
    }, 300);
  };

  // Function to navigate to the previous announcement with animation
  const prevAnnouncement = () => {
    if (animationDirection) return; // Prevent multiple animations at once
    
    setAnimationDirection('slide-out-right');
    
    // Short delay to allow animation to complete before changing content
    setTimeout(() => {
      setCurrentIndex((prev) => (prev - 1 + announcements.length) % announcements.length);
      // Set the incoming animation after content change
      setAnimationDirection('slide-in-left');
      
      // Clear animation class after it completes
      setTimeout(() => {
        setAnimationDirection('');
      }, 300);
    }, 300);
  };

  // Function to get dynamic styles based on the priority of an announcement
  const getPriorityStyles = (priority) => {
    switch (priority) {
      case 'high':
        return {
          gradientFrom: '#ff8a80',
          gradientTo: '#ff5252',
          iconColor: '#d32f2f',
          icon: '🔔'
        };
      case 'medium':
        return {
          gradientFrom: '#ffecb3',
          gradientTo: '#ffd54f',
          iconColor: '#ff6f00',
          icon: '📣'
        };
      case 'low':
        return {
          gradientFrom: '#bbdefb',
          gradientTo: '#90caf9',
          iconColor: '#1976d2',
          icon: '💬'
        };
      default:
        return {
          gradientFrom: '#e0e0e0',
          gradientTo: '#bdbdbd',
          iconColor: '#616161',
          icon: '📌'
        };
    }
  };

  // Display a stylish loading state
  if (announcements.length === 0) {
    return (
      <div className="announcement-loading" style={{
        background: 'linear-gradient(to right, #f5f7fa, #c3cfe2)',
        borderRadius: '16px',
        padding: '20px',
        textAlign: 'center',
        boxShadow: '0 10px 15px -3px rgba(0, 0, 0, 0.1)',
        animation: 'pulse 1.5s infinite ease-in-out'
      }}>
        <div style={{ fontSize: '24px', marginBottom: '8px' }}>✨</div>
        <p style={{ 
          fontWeight: '600', 
          color: '#4a5568',
          fontSize: '16px'
        }}>
          Loading announcements...
        </p>
      </div>
    );
  }

  // Get styles for current announcement
  const currentStyles = getPriorityStyles(announcements[currentIndex].priority);

  return (
    <div style={{
      marginTop: '24px',
      marginBottom: '24px',
      backgroundColor: 'white',
      borderRadius: '16px',
      boxShadow: '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)',
      overflow: 'hidden',
      border: '1px solid rgba(0, 0, 0, 0.05)',
      transition: 'all 0.3s ease',
    }}>
      {/* Header with gradient background based on priority */}
      <div style={{
        display: 'flex',
        justifyContent: 'space-between',
        alignItems: 'center',
        padding: '16px 24px',
        background: `linear-gradient(135deg, ${currentStyles.gradientFrom} 0%, ${currentStyles.gradientTo} 100%)`,
        position: 'relative',
        overflow: 'hidden',
      }}>
        {/* Animated background elements for fun */}
        <div style={{
          position: 'absolute',
          width: '120px',
          height: '120px',
          borderRadius: '50%',
          background: 'rgba(255, 255, 255, 0.15)',
          top: '-20px',
          right: '-20px',
          zIndex: 0,
        }} />
        <div style={{
          position: 'absolute',
          width: '80px',
          height: '80px',
          borderRadius: '50%',
          background: 'rgba(255, 255, 255, 0.1)',
          bottom: '-15px',
          left: '30%',
          zIndex: 0,
        }} />
        
        {/* Title and icon */}
        <div style={{ 
          display: 'flex', 
          alignItems: 'center', 
          gap: '12px',
          position: 'relative',
          zIndex: 1,
        }}>
          <span style={{ 
            fontSize: '24px', 
            backgroundColor: 'white',
            borderRadius: '50%',
            width: '40px',
            height: '40px',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'center',
            boxShadow: '0 4px 6px rgba(0, 0, 0, 0.1)'
          }}>
            {currentStyles.icon}
          </span>
          <h3 style={{ 
            margin: 0, 
            fontWeight: '700', 
            fontSize: '20px', 
            color: 'white',
            textShadow: '0 1px 2px rgba(0, 0, 0, 0.1)'
          }}>
            Announcements
          </h3>
        </div>
        
        {/* Navigation buttons */}
        <div style={{ 
          display: 'flex', 
          alignItems: 'center', 
          gap: '12px',
          position: 'relative',
          zIndex: 1,
        }}>
          <button
            onClick={prevAnnouncement}
            style={{
              width: '36px',
              height: '36px',
              backgroundColor: 'rgba(255, 255, 255, 0.85)',
              color: currentStyles.iconColor,
              borderRadius: '50%',
              border: 'none',
              cursor: 'pointer',
              transition: 'all 0.2s ease',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              fontWeight: 'bold',
              fontSize: '18px',
              boxShadow: '0 2px 5px rgba(0, 0, 0, 0.1)',
            }}
            aria-label="Previous announcement"
            onMouseOver={(e) => e.target.style.transform = 'scale(1.1)'}
            onMouseOut={(e) => e.target.style.transform = 'scale(1)'}
          >
            ←
          </button>
          
          {/* Indicator dots for navigation */}
          <div style={{ display: 'flex', gap: '6px' }}>
            {announcements.map((_, index) => (
              <span
                key={index}
                style={{
                  width: index === currentIndex ? '12px' : '8px',
                  height: '8px',
                  backgroundColor: index === currentIndex ? 'white' : 'rgba(255, 255, 255, 0.5)',
                  borderRadius: '4px',
                  transition: 'all 0.3s ease',
                }}
              />
            ))}
          </div>
          
          <button
            onClick={nextAnnouncement}
            style={{
              width: '36px',
              height: '36px',
              backgroundColor: 'rgba(255, 255, 255, 0.85)',
              color: currentStyles.iconColor,
              borderRadius: '50%',
              border: 'none',
              cursor: 'pointer',
              transition: 'all 0.2s ease',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              fontWeight: 'bold',
              fontSize: '18px',
              boxShadow: '0 2px 5px rgba(0, 0, 0, 0.1)',
            }}
            aria-label="Next announcement"
            onMouseOver={(e) => e.target.style.transform = 'scale(1.1)'}
            onMouseOut={(e) => e.target.style.transform = 'scale(1)'}
          >
            →
          </button>
        </div>
      </div>

      {/* Announcement content section */}
      <div style={{
        padding: '24px',
        position: 'relative',
        animation: animationDirection ? `${animationDirection} 0.3s ease-in-out` : 'none',
        minHeight: '200px', // Ensure content area has minimum height
      }}>
        {/* Department Badge */}
        <span style={{
          display: 'inline-block',
          backgroundColor: currentStyles.gradientFrom,
          color: currentStyles.iconColor,
          fontSize: '13px',
          fontWeight: '600',
          borderRadius: '20px',
          padding: '4px 12px',
          marginBottom: '16px',
          boxShadow: '0 2px 4px rgba(0, 0, 0, 0.05)',
          textTransform: 'uppercase',
          letterSpacing: '0.5px',
        }}>
          {announcements[currentIndex].department}
        </span>

        {/* Announcement title */}
        <h4 style={{
          fontWeight: '700',
          fontSize: '22px',
          marginBottom: '16px',
          color: '#333',
          lineHeight: 1.3,
        }}>
          {announcements[currentIndex].title}
        </h4>

        {/* Content section (supports HTML rendering) */}
        <div style={{
          fontSize: '16px',
          color: '#4a5568',
          marginBottom: '20px',
          lineHeight: 1.6,
          backgroundColor: 'rgba(0, 0, 0, 0.02)',
          padding: '16px',
          borderRadius: '8px',
          border: '1px solid rgba(0, 0, 0, 0.05)',
        }} 
        dangerouslySetInnerHTML={{
          __html: announcements[currentIndex].content,
        }} />

        {/* Footer section with date and controls */}
        <div style={{
          display: 'flex',
          justifyContent: 'space-between',
          alignItems: 'center',
          borderTop: '1px solid #edf2f7',
          paddingTop: '16px',
          marginTop: '8px',
        }}>
          {/* Posted date with icon */}
          <div style={{
            display: 'flex',
            alignItems: 'center',
            gap: '6px',
            fontSize: '14px',
            color: '#718096',
          }}>
            <span>📅</span>
            <p style={{ margin: 0 }}>
              Posted: {new Date(announcements[currentIndex].created_at).toLocaleDateString('en-US', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
              })}
            </p>
          </div>
          
          {/* Pagination indicator */}
          <div style={{
            fontSize: '14px',
            backgroundColor: '#f7fafc',
            padding: '4px 12px',
            borderRadius: '20px',
            fontWeight: '500',
            color: '#4a5568',
          }}>
            {currentIndex + 1} of {announcements.length}
          </div>
        </div>
      </div>
      
      {/* CSS for animations */}
      <style>
        {`
          @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
          }
          
          @keyframes slide-out-left {
            0% { opacity: 1; transform: translateX(0); }
            100% { opacity: 0; transform: translateX(-30px); }
          }
          
          @keyframes slide-out-right {
            0% { opacity: 1; transform: translateX(0); }
            100% { opacity: 0; transform: translateX(30px); }
          }
          
          @keyframes slide-in-left {
            0% { opacity: 0; transform: translateX(-30px); }
            100% { opacity: 1; transform: translateX(0); }
          }
          
          @keyframes slide-in-right {
            0% { opacity: 0; transform: translateX(30px); }
            100% { opacity: 1; transform: translateX(0); }
          }
        `}
      </style>
    </div>
  );
};

export default AnnouncementBanner;