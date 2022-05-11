-- 1. Create All Tables

use gadgetgainey;

-- If the tables exist, then remove them 

DROP TABLE IF EXISTS order_product;
DROP TABLE IF EXISTS product_image;
DROP TABLE IF EXISTS product; 
DROP TABLE IF EXISTS product_Category;
DROP TABLE IF EXISTS purchase_order;
DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS address;



--  https://webarchive.nationalarchives.gov.uk/ukgwa/+/http://www.cabinetoffice.gov.uk/media/254290/GDS%20Catalogue%20Vol%202.pdf


CREATE TABLE address (
    addressID int NOT NULL AUTO_INCREMENT,
	title VARCHAR(35) NOT NULL,
	firstName VARCHAR(255) NOT NULL,
	lastName VARCHAR(255) NOT NULL,
    addressLine1 VARCHAR(255) NOT NULL,
    addressLine2 VARCHAR(255),
	townCity VARCHAR(255) NOT NULL,
	county VARCHAR(255) NOT NULL,
	postcode VARCHAR(8) NOT NULL,

	
    CONSTRAINT pk_address PRIMARY KEY (addressID)
);


 
CREATE TABLE user (
    userID INT NOT NULL AUTO_INCREMENT,
	userEmail VARCHAR(254) NOT NULL,
	userPassword BINARY(60) NOT NULL,
    mainAddressID INT,
	typeOfUser VARCHAR(25) NOT NULL,

CONSTRAINT pk_user PRIMARY KEY (userID),
CONSTRAINT fk_user_AddressID FOREIGN KEY (mainAddressID) REFERENCES address(addressID)
);

CREATE TABLE purchase_order (
    orderID INT NOT NULL AUTO_INCREMENT,
    userID INT NOT NULL,
    totalPrice DECIMAL(8 , 2) NOT NULL,
    DateAndTime datetime NOT NULL,
    billingAddressID INT NOT NULL,
	deliveryAddressID INT NOT NULL,
	
	CONSTRAINT pk_purchaseOrder PRIMARY KEY (orderID),

CONSTRAINT fk_purchaseOrder_orderUserID FOREIGN KEY (userID) REFERENCES user(userID),
	CONSTRAINT fk_purchaseOrder_billingAddressID FOREIGN KEY (billingAddressID) REFERENCES address(addressID),
CONSTRAINT fk_purchaseOrder_deliveryAddressID FOREIGN KEY (deliveryAddressID) REFERENCES address(addressID)
);


CREATE TABLE product_category (
	categoryID int NOT NULL AUTO_INCREMENT,
	categoryName VARCHAR(50) NOT NULL,
	levelOfCategory smallint NOT NULL,
	categoryIDOfFirstCategory INT,
   
CONSTRAINT pk_productCategory PRIMARY KEY (categoryID),
   

CONSTRAINT fk_productCategory_categoryIDOfFirstCategory FOREIGN KEY (categoryIDOfFirstCategory) REFERENCES product_category(categoryID)
);

CREATE TABLE product (
	productID INT NOT NULL AUTO_INCREMENT,
	productTitle VARCHAR(255) NOT NULL,
	productDescription VARCHAR(10000) NOT NULL,
	productPrice DECIMAL(8 , 2) NOT NULL,
	productTotalQuantity SMALLINT NOT NULL,

	productCategoryID INT NOT NULL,
	productCarouselImageFilename VARCHAR(255) NOT NULL,
	productCarouselImageAltText VARCHAR(255) NOT NULL,
   
CONSTRAINT pk_product PRIMARY KEY (productID), 

CONSTRAINT fk_product_productCategoryID FOREIGN KEY (productCategoryID) REFERENCES product_category(CategoryID)
);

CREATE TABLE product_image (
	productImageID INT NOT NULL AUTO_INCREMENT,
	productID INT NOT NULL,
    displayOrder INT NOT NULL,
	productImageFilename VARCHAR(255) NOT NULL,
	productImageAltText VARCHAR(1000) NOT NULL,
   
CONSTRAINT pk_productImage PRIMARY KEY (productImageID),

CONSTRAINT fk_productImage_ProductID FOREIGN KEY (productID) REFERENCES product(productID)
);


CREATE TABLE order_product (
	orderProductID INT NOT NULL AUTO_INCREMENT,
	orderID INT NOT NULL,
	productID INT NOT NULL,
    productPriceAtTime DECIMAL(8 , 2) NOT NULL,
	productQuantity TINYINT NOT NULL,
   
   
CONSTRAINT pk_orderProduct PRIMARY KEY (orderProductID),

CONSTRAINT fk_orderProduct_OrderID FOREIGN KEY (orderID) REFERENCES purchase_order(orderID),
CONSTRAINT fk_orderProduct_ProductID FOREIGN KEY (productID) REFERENCES product(productID)
);
