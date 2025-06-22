# Product Requirements Document (PRD): Unified Shipping and Logistics Platform

## 1. Introduction

This document outlines the product requirements for a comprehensive Unified Shipping and Logistics Platform. The system is designed to manage the entire shipping process for a company specializing in sea, air, and land freight. It will provide a centralized platform to track shipments, manage logistics nodes, and integrate a full-fledged accounting system to monitor the financial health of each transaction. The platform will serve as the core operational tool for logistics managers, accountants, and administrative staff, streamlining workflows and improving efficiency.

---

## 2. User Personas

* **Logistics Manager:** Responsible for the day-to-day operations of shipping. They will use the system to create, and track shipments, manage routes, and oversee the movement of goods between nodes. Their primary goal is to ensure timely and cost-effective delivery.
* **Accountant:** Manages the financial aspects of the shipping process. They will use the system to generate invoices, track expenses, manage accounts payable and receivable, and generate financial reports. Their focus is on financial accuracy and profitability.
* **System Administrator:** Oversees the platform's users and settings. They are responsible for managing user accounts, defining roles and permissions, and ensuring the system runs smoothly.

---

## 3. Functional Requirements

### 3.1. Shipment Management

* **Multi-Modal Shipping:** The system must support three distinct types of shipping:
    * **Sea Shipping:** For freight transported by ocean carriers.
    * **Air Shipping:** For cargo transported by air.
    * **Land Shipping:** For goods moved by truck or rail.
* **Shipment Lifecycle Tracking:** Users must be able to create, edit, and track the status of each shipment from initiation to completion. Key statuses will include "Pending Pickup," "In Transit," "At Warehouse," "Out for Delivery," and "Delivered."
* **Node Management:** Shipments will move through a series of nodes. The system must allow for the dynamic creation and management of these nodes, which can be:
    * **Pickup Locations:** The origin of the shipment.
    * **Drop-off Locations:** The final destination of the shipment.
    * **Warehouses:** Intermediate storage and consolidation points.
    * **Customer Addresses:** Specific delivery locations for end customers.

### 3.2. Accounting and Financial Management

* **Integrated Accounting Module:** A complete accounting system will be integrated into the platform to manage all financial transactions related to shipments.
* **Invoicing:** The system will automatically generate invoices for customers upon shipment creation or delivery, with customizable templates.
* **Expense Tracking:** All costs associated with a shipment (e.g., fuel, customs fees, warehouse charges) must be recorded and allocated to the respective shipment.
* **Financial Reporting:** The platform will provide a suite of financial reports, including:
    * Profit and Loss statements per shipment, route, or time period.
    * Accounts Receivable and Accounts Payable aging reports.
    * Expense summaries.
* **Chart of Accounts:** A customizable chart of accounts will be available to categorize all financial entries properly.

### 3.3. User and Access Control

* **Role-Based Access Control (RBAC):** The system will feature a robust RBAC system. Administrators will be able to define roles and assign specific permissions to users, ensuring that employees can only access the information and functionalities relevant to their jobs.
* **User Profile Management:** Each user will have a profile with their contact information, role, and activity log.


Of course. Here is the database structure exported as a Markdown file.

Markdown

# Database Schema: Unified Shipping and Logistics Platform

Based on the Product Requirements Document (PRD), this document outlines a relational database structure for the Unified Shipping and Logistics Platform. The schema is designed to be scalable and normalized to support multi-modal shipping, node-based tracking, and integrated double-entry accounting.

The structure is presented in a generic SQL format that can be adapted to specific database systems like PostgreSQL or MySQL.

---

### Schema Organization

The database is organized into three logical groups:
1.  **Core Logistics Tables:** Manages shipments, nodes, customers, and the physical movement of goods.
2.  **User & Access Control Tables:** Manages users and their permissions.
3.  **Financial & Accounting Tables:** Manages the entire double-entry accounting system, including invoices and expenses.

---

## 1. Core Logistics Tables

These tables form the operational backbone of the application.

### `customers`
Stores information about the clients who are shippers or consignees.
```sql
CREATE TABLE customers (
    id INT PRIMARY KEY AUTO_INCREMENT,
    customer_name VARCHAR(255) NOT NULL,
    contact_person VARCHAR(255),
    email VARCHAR(255) UNIQUE NOT NULL,
    phone_number VARCHAR(50),
    address TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
nodes
A generic table to represent any physical location in the logistics chain.

SQL

CREATE TABLE nodes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    node_name VARCHAR(255) NOT NULL, -- e.g., "Tripoli Main Warehouse", "Port of Misrata", "Customer XYZ Depot"
    node_type ENUM('WAREHOUSE', 'CUSTOMER_ADDRESS', 'PORT', 'AIRPORT', 'LAND_DEPOT') NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100),
    country VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
shipments
The central table, holding high-level information for each shipment.

SQL

CREATE TABLE shipments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    reference_id VARCHAR(50) UNIQUE NOT NULL, -- User-friendly ID, e.g., "SHP-2025-06-0001"
    shipping_mode ENUM('SEA', 'AIR', 'LAND') NOT NULL,
    status ENUM('PENDING', 'IN_TRANSIT', 'AT_WAREHOUSE', 'DELIVERED', 'CANCELLED') NOT NULL DEFAULT 'PENDING',
    shipper_customer_id INT NOT NULL, -- The sender
    consignee_customer_id INT NOT NULL, -- The receiver
    origin_node_id INT NOT NULL, -- The very first pickup point
    destination_node_id INT NOT NULL, -- The final drop-off point
    estimated_departure TIMESTAMP,
    estimated_arrival TIMESTAMP,
    actual_departure TIMESTAMP,
    actual_arrival TIMESTAMP,
    created_by_user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (shipper_customer_id) REFERENCES customers(id),
    FOREIGN KEY (consignee_customer_id) REFERENCES customers(id),
    FOREIGN KEY (origin_node_id) REFERENCES nodes(id),
    FOREIGN KEY (destination_node_id) REFERENCES nodes(id),
    FOREIGN KEY (created_by_user_id) REFERENCES users(id)
);
shipment_legs
Tracks the detailed journey of a shipment from one node to the next. A single shipment can have multiple legs.

SQL

CREATE TABLE shipment_legs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    shipment_id INT NOT NULL,
    sequence_order INT NOT NULL, -- The order of this leg in the journey (1, 2, 3...)
    from_node_id INT NOT NULL,
    to_node_id INT NOT NULL,
    status ENUM('PENDING', 'IN_PROGRESS', 'COMPLETED') NOT NULL DEFAULT 'PENDING',
    departure_timestamp TIMESTAMP,
    arrival_timestamp TIMESTAMP,

    FOREIGN KEY (shipment_id) REFERENCES shipments(id) ON DELETE CASCADE,
    FOREIGN KEY (from_node_id) REFERENCES nodes(id),
    FOREIGN KEY (to_node_id) REFERENCES nodes(id),
    UNIQUE (shipment_id, sequence_order) -- Ensures each leg in a shipment has a unique sequence
);
2. User & Access Control Tables
These tables handle user authentication and authorization.

roles
Defines the user roles available in the system.

SQL

CREATE TABLE roles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    role_name VARCHAR(50) UNIQUE NOT NULL -- e.g., "Administrator", "Logistics Manager", "Accountant"
);
users
Stores user account information.

SQL

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role_id INT NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (role_id) REFERENCES roles(id)
);
3. Financial & Accounting Tables
These tables manage all financial data using a double-entry system.

chart_of_accounts
Defines all financial accounts (Assets, Liabilities, Equity, Revenue, Expenses).

SQL

CREATE TABLE chart_of_accounts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    account_number VARCHAR(20) UNIQUE NOT NULL,
    account_name VARCHAR(255) NOT NULL,
    account_type ENUM('ASSET', 'LIABILITY', 'EQUITY', 'REVENUE', 'EXPENSE') NOT NULL,
    parent_account_id INT NULL, -- For creating hierarchical accounts (e.g., "Fuel" under "Transport Expenses")
    
    FOREIGN KEY (parent_account_id) REFERENCES chart_of_accounts(id)
);
journal_entries
Represents a single financial transaction (e.g., creating an invoice, recording an expense). Each entry must have balanced debits and credits in journal_lines.

SQL

CREATE TABLE journal_entries (
    id INT PRIMARY KEY AUTO_INCREMENT,
    entry_date DATE NOT NULL,
    description TEXT NOT NULL,
    reference_type VARCHAR(50), -- e.g., "INVOICE", "EXPENSE", "PAYMENT"
    reference_id INT, -- e.g., the ID from the invoices or expenses table
    shipment_id INT, -- Optional link to a shipment for P&L reporting
    created_by_user_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (shipment_id) REFERENCES shipments(id),
    FOREIGN KEY (created_by_user_id) REFERENCES users(id)
);
journal_lines
The individual debit and credit lines for each journal entry.

SQL

CREATE TABLE journal_lines (
    id INT PRIMARY KEY AUTO_INCREMENT,
    journal_entry_id INT NOT NULL,
    account_id INT NOT NULL, -- Which account is affected
    entry_type ENUM('DEBIT', 'CREDIT') NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,

    FOREIGN KEY (journal_entry_id) REFERENCES journal_entries(id) ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES chart_of_accounts(id)
);
invoices
Stores customer invoice details. An invoice creation will trigger the creation of a corresponding journal_entry.

SQL

CREATE TABLE invoices (
    id INT PRIMARY KEY AUTO_INCREMENT,
    invoice_number VARCHAR(50) UNIQUE NOT NULL,
    shipment_id INT NOT NULL,
    customer_id INT NOT NULL,
    issue_date DATE NOT NULL,
    due_date DATE NOT NULL,
    total_amount DECIMAL(15, 2) NOT NULL,
    status ENUM('DRAFT', 'SENT', 'PAID', 'VOID') NOT NULL DEFAULT 'DRAFT',
    
    FOREIGN KEY (shipment_id) REFERENCES shipments(id),
    FOREIGN KEY (customer_id) REFERENCES customers(id)
);
expenses
Stores details of costs incurred. Recording an expense will also trigger a journal_entry.

SQL

CREATE TABLE expenses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    shipment_id INT, -- Can be linked to a shipment or be a general company expense
    shipment_leg_id INT, -- Or linked to a more specific part of a journey
    vendor_name VARCHAR(255),
    expense_date DATE NOT NULL,
    amount DECIMAL(15, 2) NOT NULL,
    description TEXT,
    account_id INT NOT NULL, -- The expense account to debit (e.g., "Fuel", "Customs Fees")
    
    FOREIGN KEY (shipment_id) REFERENCES shipments(id),
    FOREIGN KEY (shipment_leg_id) REFERENCES shipment_legs(id),
    FOREIGN KEY (account_id) REFERENCES chart_of_accounts(id)
);
Example Data Flow
A Logistics Manager (users table) creates a new shipments record.
The shipment has an origin_node_id and a destination_node_id from the nodes table.
The journey is detailed in the shipment_legs table, linking the shipment_id to a sequence of nodes.
An Accountant (users table) creates an invoices record for this shipment_id.
System Logic: Creating the invoice automatically generates a record in journal_entries with a description like "Invoice #INV-001 for Shipment #SHP-2025-06-0001".
This journal_entry gets two journal_lines:
A DEBIT to "Accounts Receivable" (chart_of_accounts).
A CREDIT to "Shipping Revenue" (chart_of_accounts).
As costs are incurred (e.g., fuel), an expenses record is created, which in turn generates another journal_entry (e.g., DEBIT "Fuel Expense", CREDIT "Cash" or "Accounts Payable").