CREATE DATABASE event_management;
USE event_management;

CREATE TABLE clubs (
    club_id INT PRIMARY KEY AUTO_INCREMENT,
    club_name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE events (
    event_id INT PRIMARY KEY AUTO_INCREMENT,
    club_id INT,
    event_name VARCHAR(100) NOT NULL,
    event_description TEXT,
    event_date DATE, 
    event_time VARCHAR (15), 
    location VARCHAR(100),
    FOREIGN KEY (club_id) REFERENCES clubs(club_id)
);

    CREATE TABLE rsvps (    
    rsvp_id INT PRIMARY KEY AUTO_INCREMENT,    
    event_id INT,    
    student_id INT,    
    first_name VARCHAR(50),    
    last_name VARCHAR(50),    
    email VARCHAR(100) UNIQUE,  
    rsvp_status ENUM('Yes', 'No', 'Maybe'),    
    FOREIGN KEY (event_id) REFERENCES events(event_id) 
);


CREATE TABLE announcements (
    announcement_id INT PRIMARY KEY AUTO_INCREMENT,
    club_id INT,
    announcement_text TEXT,
    announcement_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (club_id) REFERENCES clubs(club_id)
);


INSERT INTO clubs (club_id, club_name, description) VALUES
(1, 'Coding Club', 'The Coding Club provides a platform for students to learn programming, participate in hackathons, and collaborate on software projects. Weekly sessions focus on various programming languages, algorithms, and real-world applications. Members also engage in peer learning and coding challenges.'),
(2, 'Music Club', 'The Music Club is a vibrant community for students who are passionate about music, including singing, playing instruments, and music production. Members perform at campus events, collaborate on original compositions, and learn from guest musicians. Regular jam sessions create a lively and supportive environment for all skill levels.'),
(3, 'Art Club', 'The Art Club aims to nurture creativity and artistic expression among students. It hosts workshops on painting, sculpture, and digital art, and organizes annual exhibitions to showcase members work. The club also invites local artists for demonstrations and encourages members to explore various artistic mediums.'),
(4, 'Sports Club', 'The Sports Club encourages students to engage in physical activities and sports competitions. It offers facilities for various sports, including football, basketball, and tennis, and organizes inter-college tournaments. Regular training sessions and fitness workshops help students improve their athletic skills.'),
(5, 'Drama Club', 'The Drama Club brings together aspiring actors, directors, and writers to produce stage plays, monologues, and improvisational performances. Members participate in drama festivals and receive training from experienced theatre artists. The club promotes creativity and teamwork through regular rehearsals and script development sessions.'),
(6, 'Photography Club', 'The Photography Club is dedicated to capturing moments through the lens. Members learn various photography techniques, participate in photo walks, and showcase their work in exhibitions. The club also organizes sessions on post-processing, editing, and visual storytelling, fostering a deeper appreciation for the art of photography.'),
(7, 'Debate Club', 'The Debate Club fosters critical thinking and public speaking skills among students. Members engage in structured debates on contemporary issues, participate in intercollegiate competitions, and attend workshops on argumentation and persuasion. The club promotes intellectual growth and effective communication.'),
(8, 'Environmental Club', 'The Environmental Club focuses on raising awareness about sustainability and environmental conservation. Members participate in clean-up drives, tree-planting initiatives, and workshops on eco-friendly practices. The club collaborates with local organizations to promote environmental stewardship.');


INSERT INTO events (event_id, club_id, event_name, event_description, event_date, event_time, location) VALUES(1, 1, 'Hackathon 2024', 'A 48-hour hackathon where students form teams to build innovative software solutions. Prizes for the best projects.', '2024-10-12', '4:00 PM', 'Main Auditorium'),
(2, 2, 'Open Mic Night', 'An evening of live music performances by club members, featuring solos, duets, and group acts.', '2024-12-28', '11:30 AM', 'Student Lounge'),
(3, 3, 'Annual Art Exhibition', 'Showcasing artwork created by club members throughout the year, including paintings, sculptures, and digital art.', '2024-11-15', '5:00 PM', 'Art Gallery'),
(4, 4, 'Inter-College Football Tournament', 'A week-long football tournament featuring teams from various colleges. The winning team receives a championship trophy.', '2024-12-05', '2:00 PM', 'Sports Field'),
(5, 5, 'Shakespeare Festival', 'A series of performances and workshops celebrating the works of William Shakespeare, featuring scenes from his plays.', '2024-11-20', '3:20 PM', 'Black Box Theatre'),
(6, 6, 'Photography Workshop: Landscape Photography', 'A hands-on workshop focusing on landscape photography techniques, including composition and lighting.', '2024-12-30', '6:00 PM', 'Room 204'),
(7, 7, 'Debate Competition: Future of AI', 'A formal debate competition where teams discuss the implications of artificial intelligence on society.', '2024-11-25', '7:30 PM', 'Lecture Hall A'),
(8, 8, 'Campus Clean-Up Drive', 'A volunteer-driven initiative to clean up the campus and promote environmental responsibility among students.', '2024-11-22', '1:00 PM', 'Campus Grounds');


INSERT INTO announcements (announcement_id, club_id, announcement_text, announcement_date) VALUES
(1, 1, 'The Coding Club is excited to announce our upcoming hackathon. Join us to compete for amazing prizes!', CURRENT_TIMESTAMP),
(2, 2, 'Join us for Open Mic Night! All music enthusiasts are welcome to perform or enjoy an evening of live music.', CURRENT_TIMESTAMP),
(3, 3, 'Submissions are open for the Annual Art Exhibition. Submit your artwork by November 1st to be considered.', CURRENT_TIMESTAMP),
(4, 4, 'The Sports Club is organizing an inter-college football tournament. The tournament will take place starting November 15th.', CURRENT_TIMESTAMP),
(5, 5, 'Auditions for the Shakespeare Festival are now open. Showcase your acting talents.', CURRENT_TIMESTAMP),
(6, 6, 'The Photography Club is hosting a landscape photography workshop. Limited seats available, so be sure to join!', CURRENT_TIMESTAMP),
(7, 7, 'The Debate Club invites you to participate in our competition on the Future of AI. The event is happening soon.', CURRENT_TIMESTAMP),
(8, 8, 'The Environmental Club is organizing a campus clean-up drive. Join us to make a positive impact on our environment.', CURRENT_TIMESTAMP);



