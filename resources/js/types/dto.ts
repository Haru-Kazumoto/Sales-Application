export interface RequestLoginDto {
    username: string;
    password: string;
}

export interface POCOList {
    poco_number: string;
    pt: string;
    salesman: string;
    customer: string;
}

export interface ProductList {
    product_name: string;
    amount: number;
    unit: string;
    price: string;
    discount: number;
    net:number;
    total: number;
}