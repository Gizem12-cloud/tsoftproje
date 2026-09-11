export interface PaginationMeta {
    current_page: number;
    last_page: number;
    total: number;
  }
  
  export interface PaginatedResponse<T> {
    data: T[];
    meta: PaginationMeta;
  }