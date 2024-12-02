export class Solucion {
    private _idSolucion : number;
    private _nombre: string;
    constructor(values: any) {
        this._idSolucion  = values['idSolucion '] || 0;
        this._nombre = values['nombre'] || '';
    }
    get idsoluciones (): number {
        return this._idSolucion ;
    }

    set idsoluciones (value: number) {
        this._idSolucion  = value;
    }

    get nombre (): string {
        return this._nombre;
    }

    set nombre(value: string) {
        this._nombre = value;
    }
}
