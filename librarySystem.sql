-- Sql for the online library system

-- Drop required Tables (If this causes syntax errors comment out these DROPS)

DROP TABLE reservations;
DROP TABLE users;
DROP TABLE books;
DROP TABLE category;

-- Create all Tables

-- Books
CREATE TABLE `books` (
  `IBSN` varchar(20) NOT NULL,
  `BookTitle` varchar(50) NOT NULL,
  `Auther` varchar(50) NOT NULL,
  `Edition` int(2) NOT NULL,
  `Year` int(4) NOT NULL,
  `Category` int(3) NOT NULL,
  `Reserved` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Users
CREATE TABLE `users` (
  `Username` varchar(30) NOT NULL,
  `Password` varchar(40) NOT NULL,
  `FirstName` varchar(30) NOT NULL,
  `Surname` varchar(40) NOT NULL,
  `AddressLine1` varchar(150) NOT NULL,
  `AddressLine2` varchar(100) NOT NULL,
  `City` varchar(50) NOT NULL,
  `Telephone` int(10) NOT NULL,
  `Mobile` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Reservations
CREATE TABLE `reservations` (
  `Username` varchar(30) NOT NULL,
  `IBSN` varchar(20) NOT NULL,
  `ReserveDate` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Categories
CREATE TABLE `category` (
  `CategoryID` int(3) NOT NULL,
  `CategoryDesc` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
  
-- Add Primary Keys

ALTER TABLE `books`
  ADD PRIMARY KEY (`IBSN`);
  
ALTER TABLE `users`
  ADD PRIMARY KEY (`Username`);

ALTER TABLE `reservations`
  ADD PRIMARY KEY (`IBSN`);
 
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);
  
-- Add Foreign Keys
  
ALTER TABLE books
ADD FOREIGN KEY category_books_fk(Category)
REFERENCES category(CategoryID);

ALTER TABLE reservations
ADD FOREIGN KEY username_reservations_fk(Username)
REFERENCES users(username);

ALTER TABLE reservations
ADD FOREIGN KEY ibsn_reservations_fk(IBSN)
REFERENCES books(IBSN);
 
-- Insert Table Data

-- Category
INSERT INTO `category` (`CategoryID`, `CategoryDesc`) VALUES
(1, 'Health'),
(2, 'Business'),
(3, 'Biography'),
(4, 'Technology'),
(5, 'Travel'),
(6, 'Self-Help'),
(7, 'Cookery'),
(8, 'Fiction');

-- Books
INSERT INTO `books` (`IBSN`, `BookTitle`, `Auther`, `Edition`, `Year`, `Category`, `Reserved`) VALUES
('093-403992', 'Computers in Business', 'Alicia Oneill', 3, 1997, 3, 'N'),
('23472-8729', 'Exploring Peru', 'Stephanie Birchi', 4, 2005, 5, 'N'),
('237-34823', 'Business Strategy', 'Joe Peppard', 2, 2002, 2, 'N'),
('23u8-923849', 'A Guide to nutrition', 'John Thorpe', 4, 1997, 1, 'N'),
('2983-3494', 'Cooking for children', 'Anabelle Sharpe', 1, 2003, 7, 'N'),
('82n8-308', 'Computers for Idiots', 'Susan O''Neill', 5, 1998, 4, 'N'),
('9823-23984', 'My Life in Picture', 'Kevin Graham', 8, 2004, 1, 'N'),
('9823-2403-0', 'DaVinci Code', 'Dan Brown', 1, 2003, 8, 'N'),
('9823-98345', 'How to Cook Italian Food', 'Jamie Oliver', 2, 2005, 7, 'Y'),
('9823-98487', 'Optimising Your Business', 'Cleo Blair', 1, 2001, 2, 'N'),
('98234-029384', 'My Ranch in Texas', 'George Bush', 1, 2005, 1, 'Y'),
('988745-234', 'Tara Road', 'Maeve Binchy', 4, 2002, 8, 'N'),
('993-004-00', 'My Life in Bits', 'John Smith', 1, 2001, 1, 'N'),
('9987-0039882', 'Shooting History', 'Jon Snow', 1, 2003, 1, 'N');

-- Users
INSERT INTO `users` (`Username`, `Password`, `FirstName`, `Surname`, `AddressLine1`, `AddressLine2`, `City`, `Telephone`, `Mobile`) VALUES
('alanjmckenna', 't1234s', 'Alan', 'McKenna', '38 Cranley Road', 'Fairview', 'Dublin', 9998377, 856625567),
('joecrotty', 'kj7899', 'Joseph', 'Crotty', 'Apt 5 Clyde Road', 'DonnyBrook', 'Dublin', 8887889, 876654456),
('tommy100', '123456', 'Tom', 'Behan', '14 Hyde Road', 'Dalkey', 'Dublin', 9983747, 876738782);

-- Reservations
INSERT INTO `reservations` (`Username`, `IBSN`, `ReserveDate`) VALUES
('joecrotty', '9823-98345', '2016-04-18'),
('tommy100', '98234-029384', '2015-11-13');


















