export class Departamento {
    private _idDepartamento: number;
    private _nombreDepartamento: string;
    constructor(values: any) {
        this._idDepartamento = values['idDepartamento'] || 0;
        this._nombreDepartamento = values['nombreDepartamento'] || '';
    }
    get idDepartamento(): number {
        return this._idDepartamento;
    }

    set idDepartamento(value: number) {
        this._idDepartamento = value;
    }

    get nombreDepartamento(): string {
        return this._nombreDepartamento;
    }

    set nombreDepartamento(value: string) {
        this._nombreDepartamento = value;
    }
}
