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

export interface Lookup {
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

export interface SubSalesOrderProducts extends POProduct {}