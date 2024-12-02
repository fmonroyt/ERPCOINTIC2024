export class Empresa {
    
    private _idempresaExt: number;
    private _nombre: string;
    private _telefono: number;
    private _ciudad: string;
    private _pais: string;
    private _idusuario: number;
    
    constructor(values: any) {
        this._idempresaExt = values['idEmpresaExt'] || 0;
        this._nombre = values['nombre'] || '';
        this._telefono = values['telefono'] || '';
        this._ciudad = values['ciudad'] || '';
        this._pais = values['pais'] || '';
        this._idusuario = values['idusuario'] || 0;
        
    }
    
    get idempresaExt(): number {
        return this._idempresaExt;
    }

    set idempresaExt(value: number) {
        this._idempresaExt = value;
    }
    get nombre(): string {
        return this._nombre;
    }
    
    set nombre(value: string) {
        this._nombre = value;
    }
    get telefono(): number {
        return this._telefono;
    }
    
    set telefono(value: number) {
        this._telefono = value;
    }
    get ciudad(): string {
        return this._ciudad;
    }
    
    set ciudad(value: string) {
        this._ciudad = value;
    }
    get pais(): string {
        return this._pais;
    }
    
    set pais(value: string) {
        this._pais = value;
    }
    
    get idusuario(): number {
        return this._idusuario;
    }
    
    set idusuario(value: number) {
        this._idusuario = value;
    }
}
