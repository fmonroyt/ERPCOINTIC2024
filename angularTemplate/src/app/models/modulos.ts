export class Modulos {
    private _idModulo: number;
    private _nombreModulo: string;
    constructor(values:any) {
        this._idModulo = values['idModulo']||0;
        this._nombreModulo = values['nombreModulo']||'';
    }

    get idModulo(): number {
        return this._idModulo;
    }

    set idModulo(value: number) {
        this._idModulo = value;
    }

    get nombreModulo(): string {
        return this._nombreModulo;
    }

    set nombreModulo(value: string) {
        this._nombreModulo = value;
    }
}
