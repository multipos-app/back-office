# multiPOS/cakePHP POS (point of sale) Back Office
This project is the back office component of multipos-app/pos a cloud based back office for reporting, POS data management and control. The system is a mature application having been installed in retail and quick serve environments in the USA and Denmark. 

## Updates

- 04/02, Complete work on menu creation and edits, lots of little UI fixes 
- Back office just received a major face lift based on bootstrapmade NiceAdmin.

## Reporting
- Dashboard, sales summary for the last 7 days and YTD
- Item Sales
- Hourly Sales
- Transactions, transaction detail, with receipt image

## Store operations
- Item, department, employee, employee profile, tax, VAT
- Discounts, %, fixed amount, mix and match (buy 2, get one free)
- Item markdown by % or fixed
- POS designer, menu manager
- Batch update
- Item inventory
- Account managment
- Multi site (store) support
- Customer management
- Suppliers/Orders
- Customize receipt, header and footer
- Linked items (deposits, packages)
- Sale discount percent (i.e. employee disount)

## Pricing Options
- Standard pricing, scanned, enter sku or tied to a button
- Size, i.e. Fountain drinks, small, medium, large
- Metric, pound, liter, kg

## Install

- Create a LAMP server
- Install cakePHP, https://book.cakephp.org/4/en/installation.html
- Lay down back office on top of that
- Set up database in config/app.php
- Set up routes in config/routes.php
- create the merchant database (provides merchant logins)
- create a database for each merchant m_<merchant id from merchant db>
