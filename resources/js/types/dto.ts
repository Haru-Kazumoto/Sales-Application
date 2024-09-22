export interface RequestLoginDto {
    username: string;
    password: string;
}

export interface RequestRegisterDto {
    fullname: string;
    username: string;
    email: string;
    password: string;
    role_id: number | null;
}

export interface POCOList {
    poco_number: string;
    pt: string;
    salesman: string;
    customer: string;
}

export interface ProductCustomerList {
    product_name: string;
    amount: number;
    unit: string;
    price: string;
    discount: number;
    net:number;
    total: number;
}

export interface CustomerList {
    client_code: string;
    customer_name: string;
    segmen: string;
    address: string;
    phone_number: string;
    pic: string;
}

export interface ProductList {
    sku: string;
    product_name: string;
    package: string;
    user_price: string;
    retail_price: string;
    restaurant_price: string;
    stock: number;
}

export interface UserCreateDTO {
    fullname: string;
    username: string;
    email: string;
    password: string;
    role_id: number;
    division_id: number;
}

export interface UserUpdateDTO extends UserCreateDTO {
    password_confirmation: string;
}

export interface RoleCreateDTO {
    role_name: string;
}

export interface RoleUpdateDTO extends RoleCreateDTO {}

export interface DivisionCreateDTO {
    division_name: string;
}

export interface DivisionUpdateDTO extends DivisionCreateDTO {}