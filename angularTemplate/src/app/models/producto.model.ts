export class Producto {
    
    private _idProducto_Fort: number;
    private _Unit: string;
    private _SKU: string;
    private _Description: string;
    private _Price: string;
    private _Contract_1Yr: string;
    private _Contract_2Yr: string;
    private _Contract_3Yr: string;
    private _Contract_4Yr: string;
    private _Contract_5Yr: string;
    private _Comments: string;
    private _Category: string;
    private _nombreSolucion: string;
    private _idrel: string;
    constructor(values: any) {
        this._idProducto_Fort = values['idProducto_Fort'] || 0;
        this._Unit = values['Unit'] || '';
        this._SKU = values['SKU'] || '';
        this._Description = values['Description'] || '';
        this._Price = values['Price'] || '';
        this._Contract_1Yr = values['Contract_1Yr'] || '';
        this._Contract_2Yr = values['Contract_2Yr'] || '';
        this._Contract_3Yr = values['Contract_3Yr'] || '';
        this._Contract_4Yr = values['Contract_4Yr'] || '';
        this._Contract_5Yr = values['Contract_5Yr'] || '';
        this._Comments = values['Comments'] || '';
        this._Category = values['Category'] || '';
        this._nombreSolucion = values['nombreSolucion'] || '';
        this._idrel = values['idrel'] || '';
    }
    get idProducto_Fort(): number {
        return this._idProducto_Fort;
    }

    set idProducto_Fort(value: number) {
        this._idProducto_Fort = value;
    }

    get Unit(): string {
        return this._Unit;
    }

    set Unit(value: string) {
        this._Unit = value;
    }
    get SKU(): string {
        return this._SKU;
    }

    set SKU(value: string) {
        this._SKU = value;
    }
    get Description(): string {
        return this._Description;
    }

    set Description(value: string) {
        this._Description = value;
    }
    set Price(value: string) {
        this._Price = value;
    }
    get Price(): string {
        return this._Price;
    }
    set Contract_1Yr(value: string) {
        this._Contract_1Yr = value;
    }
    get Contract_1Yr(): string {
        return this._Contract_1Yr;
    }
    set Contract_2Yr(value: string) {
        this._Contract_2Yr = value;
    }
    get Contract_2Yr(): string {
        return this._Contract_2Yr;
    }
    set Contract_3Yr(value: string) {
        this._Contract_3Yr = value;
    }
    get Contract_3Yr(): string {
        return this._Contract_3Yr;
    }
    set Contract_4Yr(value: string) {
        this._Contract_4Yr = value;
    }
    get Contract_4Yr(): string {
        return this._Contract_4Yr;
    }
    set Contract_5Yr(value: string) {
        this._Contract_5Yr = value;
    }
    get Contract_5Yr(): string {
        return this._Contract_5Yr;
    }
    set Comments(value: string) {
        this._Comments = value;
    }
    get Comments(): string {
        return this._Comments;
    }
    set Category(value: string) {
        this._Category = value;
    }
    get Category(): string {
        return this._Category;
    }
    set idrel(value: string) {
        this._idrel = value;
    }
    get idrel(): string {
        return this._idrel;
    }
    
    

}
