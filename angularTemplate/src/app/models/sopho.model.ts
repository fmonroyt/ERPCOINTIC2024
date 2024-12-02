export class Sopho {
    private _idproduct_Sophos: number;
    private _SKU: string;
    private _Description: string;
    private _Precio: number;
    private _Category	: string;
    private _nombreSolucion: string;
    private _idrel: string;

    constructor(values: any) {
        this._idproduct_Sophos = values['idproduct_Sophos'] || 0;
        this._SKU = values['SKU'] || '';
        this._Description = values['Description'] || '';
        this._Precio = values['Precio'] || '';
        this._Category= values['Category'] || '';
        this._nombreSolucion = values['nombreSolucion'] || '';
        this._idrel = values['idrel'] || '';
    }
    get idproduct_Sophos(): number {
        return this._idproduct_Sophos;
    }

    set idproduct_Sophos(value: number) {
        this._idproduct_Sophos = value;
    }

    get SKU(): string {
        return this._SKU;
    }

    set SKU(value: string) {
        this._SKU= value;
    }
    get Description(): string{
        return this._Description;
    }
    
    set Description(value: string) {
        this._Description = value;
    }
    get Precio(): number {
        return this._Precio;
    }
    
    set Precio(value: number) {
        this._Precio = value;
    }
    get Category(): string{
        return this._Category;
    }
    
    set Category(value: string) {
        this._Category = value;
    }
    get nombreSolucion(): string{
        return this._nombreSolucion;
    }
    
    set nombreSolucion(value: string) {
        this._nombreSolucion = value;
    }
    get idrel(): string{
        return this._idrel;
    }
    
    set idrel(value: string) {
        this._idrel = value;
    }
}
