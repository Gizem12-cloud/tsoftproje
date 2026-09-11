export interface OrderItem {
    id: number;
    product_id: number;
    product_name: string;
    unit_price: string;
    quantity: number;
  }
  
  export interface Order {
    id: number;
    status: string;
    total_amount: string;
    customer_name: string;
    customer_email: string;
    created_at: string;
    items: OrderItem[];
  }