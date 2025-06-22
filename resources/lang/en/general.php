<?php

return [
    // General
    'dashboard' => 'Dashboard',
    'create' => 'Create',
    'edit' => 'Edit',
    'delete' => 'Delete',
    'save' => 'Save',
    'cancel' => 'Cancel',
    'submit' => 'Submit',
    'back' => 'Back',
    'actions' => 'Actions',
    'details' => 'Details',
    'status' => 'Status',
    'date' => 'Date',

    // Navigation
    'navigation' => [
        'shipments' => 'Shipments',
        'customers' => 'Customers',
        'nodes' => 'Logistics Nodes',
        'accounting' => 'Accounting',
        'invoices' => 'Invoices',
        'expenses' => 'Expenses',
        'users' => 'Users',
        'roles' => 'Roles',
        'chart_of_accounts' => 'Chart of Accounts',
    ],

    // Shipments
    'shipment' => [
        'title' => 'Shipment',
        'title_plural' => 'Shipments',
        'reference_id' => 'Reference ID',
        'status' => 'Status',
        'shipping_mode' => 'Shipping Mode',
        'sea' => 'Sea',
        'air' => 'Air',
        'land' => 'Land',
        'shipper' => 'Shipper',
        'consignee' => 'Consignee',
        'origin' => 'Origin',
        'destination' => 'Destination',
        'created_at' => 'Created at',
        'estimated_departure' => 'Estimated Departure',
        'estimated_arrival' => 'Estimated Arrival',
        'actual_departure' => 'Actual Departure',
        'actual_arrival' => 'Actual Arrival',
        'created_by' => 'Created by',
        'change_status' => 'Change Status',
        'change_status_tooltip' => 'Change shipment status',
        'status_updated' => 'Status Updated',
        'status_changed_to' => 'Shipment status changed to',
        'statuses' => [
            'pending' => 'Pending',
            'in_transit' => 'In Transit',
            'at_warehouse' => 'At Warehouse',
            'delivered' => 'Delivered',
            'cancelled' => 'Cancelled',
        ],
    ],

    // Shipment Legs
    'shipment_leg' => [
        'title' => 'Shipment Leg',
        'title_plural' => 'Shipment Legs',
        'sequence_order' => 'Sequence Order',
        'from_node' => 'From Node',
        'to_node' => 'To Node',
        'status' => 'Status',
        'departure_timestamp' => 'Departure Timestamp',
        'arrival_timestamp' => 'Arrival Timestamp',
        'statuses' => [
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
        ],
    ],

    // Customers
    'customer' => [
        'title' => 'Customer',
        'title_plural' => 'Customers',
        'name' => 'Name',
        'contact_person' => 'Contact Person',
        'email' => 'Email',
        'phone_number' => 'Phone Number',
        'address' => 'Address',
    ],

    // Nodes
    'node' => [
        'title' => 'Node',
        'title_plural' => 'Nodes',
        'name' => 'Name',
        'type' => 'Type',
        'address' => 'Address',
        'city' => 'City',
        'country' => 'Country',
        'types' => [
            'warehouse' => 'Warehouse',
            'customer_address' => 'Customer Address',
            'port' => 'Port',
            'airport' => 'Airport',
            'land_depot' => 'Land Depot',
        ],
    ],

    // Accounting
    'chart_of_account' => [
        'title' => 'Account',
        'title_plural' => 'Chart of Accounts',
        'account_number' => 'Account Number',
        'account_name' => 'Account Name',
        'account_type' => 'Account Type',
        'parent_account' => 'Parent Account',
        'types' => [
            'asset' => 'Asset',
            'liability' => 'Liability',
            'equity' => 'Equity',
            'revenue' => 'Revenue',
            'expense' => 'Expense',
        ],
    ],

    // Journal Entries
    'journal_entry' => [
        'title' => 'Journal Entry',
        'title_plural' => 'Journal Entries',
        'entry_date' => 'Entry Date',
        'description' => 'Description',
        'reference_type' => 'Reference Type',
        'reference_id' => 'Reference ID',
        'shipment' => 'Related Shipment',
        'created_by' => 'Created by',
    ],

    // Journal Lines
    'journal_line' => [
        'title' => 'Journal Line',
        'title_plural' => 'Journal Lines',
        'account' => 'Account',
        'entry_type' => 'Entry Type',
        'amount' => 'Amount',
        'types' => [
            'debit' => 'Debit',
            'credit' => 'Credit',
        ],
    ],

    // Invoices
    'invoice' => [
        'title' => 'Invoice',
        'title_plural' => 'Invoices',
        'invoice_number' => 'Invoice Number',
        'shipment' => 'Related Shipment',
        'customer' => 'Customer',
        'issue_date' => 'Issue Date',
        'due_date' => 'Due Date',
        'total_amount' => 'Total Amount',
        'status' => 'Status',
        'generate_invoice' => 'Generate Invoice',
        'generate_invoice_tooltip' => 'Create an invoice for this shipment',
        'statuses' => [
            'draft' => 'Draft',
            'sent' => 'Sent',
            'paid' => 'Paid',
            'void' => 'Void',
        ],
    ],

    // Expenses
    'expense' => [
        'title' => 'Expense',
        'title_plural' => 'Expenses',
        'shipment' => 'Related Shipment',
        'shipment_leg' => 'Related Shipment Leg',
        'vendor_name' => 'Vendor Name',
        'expense_date' => 'Expense Date',
        'amount' => 'Amount',
        'description' => 'Description',
        'account' => 'Account',
    ],

    // Users
    'user' => [
        'title' => 'User',
        'title_plural' => 'Users',
        'full_name' => 'Full Name',
        'email' => 'Email',
        'password' => 'Password',
        'role' => 'Role',
        'active' => 'Active',
    ],

    // Roles
    'role' => [
        'title' => 'Role',
        'title_plural' => 'Roles',
        'name' => 'Name',
    ],

    // Widgets
    'widgets' => [
        'stats_overview' => [
            'total_shipments' => 'Total Shipments',
            'total_shipments_description' => 'All shipments in the system',
            'active_customers' => 'Active Customers',
            'active_customers_description' => 'Total registered customers',
            'revenue' => 'Revenue',
            'revenue_description' => 'Total invoiced amount',
            'expenses' => 'Expenses',
            'expenses_description' => 'Total expenses',
        ],
        'financial_overview' => 'Financial Overview',
        'shipments_by_type' => 'Shipments by Type',
        'latest_shipments' => 'Latest Shipments',
        'created' => 'Created',
        'view' => 'View',
        'view_legs' => 'View Legs',
        'view_shipment_legs' => 'View Shipment Legs',
        'chart_labels' => [
            'revenue' => 'Revenue',
            'expenses' => 'Expenses',
            'shipments_by_type' => 'Shipments by Type',
        ],
    ],

    // Common UI elements
    'ui' => [
        'search' => 'Search',
        'filter' => 'Filter',
        'export' => 'Export',
        'import' => 'Import',
        'bulk_actions' => 'Bulk Actions',
        'no_records_found' => 'No records found',
        'loading' => 'Loading...',
        'confirm' => 'Confirm',
        'yes' => 'Yes',
        'no' => 'No',
        'close' => 'Close',
        'select_option' => 'Select an option',
        'required_field' => 'This field is required',
    ],
];
