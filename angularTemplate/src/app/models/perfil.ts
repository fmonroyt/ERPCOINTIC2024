export class Perfil {
    private _idPerfil: number;
    private _nombrePerfil: string;
    constructor(values: any) {
        this._idPerfil = values['idPerfil'] || 0;
        this._nombrePerfil = values['nombrePerfil'] || '';
    }
    get idPerfil(): number {
        return this._idPerfil;
    }

    set idPerfil(value: number) {
        this._idPerfil = value;
    }

    get nombrePerfil(): string {
        return this._nombrePerfil;
    }

    set nombrePerfil(value: string) {
        this._nombrePerfil = value;
    }
}

