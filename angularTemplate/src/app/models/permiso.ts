export class Permiso {
    private _idModulo: number;
    private _mostrar: boolean;
    private _alta: boolean;
    private _detalle: boolean;
    private _editar: boolean;
    private _eliminar: boolean;
    constructor(values: any) {
        this._idModulo = values['idModulo'] || 0;
        this._mostrar = values['mostrar'] || false;
        this._alta = values['alta'] || false;
        this._detalle = values['detalle'] || false;
        this._editar = values['editar'] || false;
        this._eliminar = values['eliminar'] || false;
    }

    get idModulo(): number {
        return this._idModulo;
    }

    set idModulo(value: number) {
        this._idModulo = value;
    }

    get mostrar(): boolean {
        return this._mostrar;
    }

    set mostrar(value: boolean) {
        this._mostrar = value;
    }

    get alta(): boolean {
        return this._alta;
    }

    set alta(value: boolean) {
        this._alta = value;
    }

    get detalle(): boolean {
        return this._detalle;
    }

    set detalle(value: boolean) {
        this._detalle = value;
    }

    get editar(): boolean {
        return this._editar;
    }

    set editar(value: boolean) {
        this._editar = value;
    }

    get eliminar(): boolean {
        return this._eliminar;
    }

    set eliminar(value: boolean) {
        this._eliminar = value;
    }
}
