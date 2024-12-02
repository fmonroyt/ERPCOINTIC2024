export class Marca {
    private _idMarcas : number;
    private _Nombre: string;
    constructor(values: any) {
        this._idMarcas  = values['idMarcas'] || 0;
        this._Nombre = values['Nombre'] || '';
    }
    get idMarcas (): number {
        return this._idMarcas ;
    }

    set idMarcas (value: number) {
        this._idMarcas  = value;
    }

    get Nombre (): string {
        return this._Nombre;
    }

    set Nombre(value: string) {
        this._Nombre = value;
    }
}
