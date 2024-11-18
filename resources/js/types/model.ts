export interface PurchaseOrder {
    id?: number;
    purchase_order_number: string;
    supplier: string;
    storehouse: string;
    located: string;
    purchase_order_date: string | null;
    send_date: string | null;
    payment_term: string;
    due_date: string | null;
    transportation: string;
    sender: string;
    delivery_type: string;
    employee_name: string;
    notes: string;
    sub_total: number;
    total_price: number;
    total_ppn: number;
    purchase_order_products: POProduct[];
}

export interface Transactions {
    id?: number;
    document_code: string;
    correlation_id?: number | null;
    created_by?: number | null;
    updated_by?: number | null;
    term_of_payment: string;
    due_date: string;
    description: string;
    sub_total: number | null;
    total: number | null;
    tax_amount: number | null;
    transaction_type_id?: number;
    transaction_details: TransactionDetail[];
    transaction_items?: TransactionItems[];
}

export interface Parties {
    id?: number;
    code: string;
    name: string;
    legality: string;
    number_plate?: string;
    type_parties?: string;
    phone?: string;
    fax?: string;
    handphone?: string;
    email?: string;
    website?: string;
    npwp?: string;
    contact_person?: string;
    term_payment?: number;
    address?: string;
    postal_code?: string;
    city?: string;
    province?: string;
    country?: string;
    description?: string;
    parties_group_id?: number;
    parties_group?: PartiesGroup;
}

export interface PartiesGroup {
    id?: number;
    name: string;
    description?: string;
}

export interface TransactionType {
    id?: number;
    name: string;
}

export interface TransactionDetail {
    id?: number;
    name: string;
    category: string;
    total_discount_1?: number | null;
    total_discount_2?: number | null;
    total_discount_3?: number | null;
    value?: string;
    data_type: string;
    transactions_id?: number;
    transaction?: Transactions;
}

export interface TransactionItems {
    id?: number;
    unit?: string;
    quantity?: number | null;
    tax_amount?: number | null;
    amount?: number | null;
    item_gap?: number | null;
    gap_description?: string | null;
    discount_1?: number | null;
    discount_2?: number | null;
    discount_3?: number | null;
    total_discount_1?: number | null;
    total_discount_2?: number | null;
    total_discount_3?: number | null;
    transactions_id?: number | null;
    product_id?: number | null;
    tax_id?: number | null;
    tax_value?: number | null;
    total_price?: number | null;
    transaction?: Transactions;
    product?: Products;
    tax?: Tax;
    product_journals?: any[];
}

export interface Tax {
    id?: number;
    name: string;
    value: number;
}

export interface Products {
    id?: number;
    unit: string;
    name: string;
    code: string;
    tax_id?: number | null;
    product_type_id?: number | null;
    transaction_items?: TransactionItems[];
}

export interface Lookup {
    id?: number;
    label: string;
    value: string;
    category?: string;
}

export interface POProduct {
    product_code: string;
    product_name: string;
    amount: number | null;
    package: string;
    product_price: number | null;
    total_price: number | null;
    ppn: number | null;
}

export interface Storehouse {
    name: string;
}

export interface Division {
    id: number;
    division_name: string;
    division_uid: string;
}

export interface Role {
    id: number;
    role_name: string;
    role_uid: string;
}

export interface Flash {
    success: string;
    failed: string;
    info: string;
}

export interface User {
    id: number;
    fullname: string;
    username: string;
    user_uid: string;
    phone: string;
    email: string;
    role: Role;
    division: Division
}

export interface MenuAccess {
    menu_name: string;
    menu_key: string;
    menu_icon: string;
    menu_url: string;
}

export interface SubSalesOrder {
    id?: number;
    purchase_order_number: string;
    proof_number: string;
    sales_order_number: string;
    order_date: string | null;
    located: string;
    supplier: string;
    storehouse: string;
    send_date: string | null;
    transportation: string;
    sender: string;
    delivery_type: string;
    employee_name: string;
    sub_total: number;
    total_after_ppn: number;
    note: string;
    total_price: number;
    sub_sales_order_products: SubSalesOrderProducts[];
}

export interface SubSalesOrderProducts extends POProduct { }