export class Relmarca {
    
    private _idrel: number;
    private _nombreSolucion: string;
    constructor(values: any) {
        this._idrel = values['idrel'] || 0;
        this._nombreSolucion = values['nombreSolucion'] || '';
    }
    get idrel(): number {
        return this._idrel;
    }

    set idrel(value: number) {
        this._idrel = value;
    }

    get nombreSolucion(): string {
        return this._nombreSolucion;
    }

    set nombreSolucion(value: string) {
        this._nombreSolucion = value;
    }
}
