export class Sonic {
    private _idproducto_Sonic: number;
    private _SKU: string;
    private _Description: string;
    private _Precio: number;
    private _HardwareorSoftware	: string;
    private _nombreSolucion: string;
    private _idrel: string;

    constructor(values: any) {
        this._idproducto_Sonic = values['idproducto_Sonic'] || 0;
        this._SKU = values['SKU'] || '';
        this._Description = values['Description'] || '';
        this._Precio = values['Precio'] || '';
        this._HardwareorSoftware= values['HardwareorSoftware'] || '';
        this._nombreSolucion = values['nombreSolucion'] || '';
        this._idrel = values['idrel'] || '';
    }
    get idproducto_Sonic(): number {
        return this._idproducto_Sonic;
    }

    set idproducto_Sonic(value: number) {
        this._idproducto_Sonic = value;
    }
    get SKU(): string{
        return this._SKU;
    }
    
    set SKU(value: string) {
        this._SKU = value;
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
    get HardwareorSoftware(): string{
        return this._HardwareorSoftware;
    }
    
    set HardwareorSoftware(value: string) {
        this._HardwareorSoftware = value;
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
