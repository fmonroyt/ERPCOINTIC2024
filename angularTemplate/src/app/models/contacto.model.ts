export class Contacto {

    private _idcontacto: number;
    private _nombre: string;
    private _apellido: string;
    private _correo: string;
    private _telefono: number;
    private _idempresaext: number;

    constructor(values: any) {
        this._idcontacto = values['idcontacto'] || '';
        this._nombre = values['nombre'] || '';
        this._apellido = values['apellido'] || '';
        this._correo = values['correo'] || '';
        this._telefono = values['telefono'] || '';
        this._idempresaext = values['idempresaext'] || 0;

}

get idcontacto(): number {
    return this._idcontacto;
}

set idcontacto(value: number) {
    this._idcontacto = value;
}
get nombre(): string {
    return this._nombre;
}

set nombre(value: string) {
    this._nombre = value;
}
get apellido(): string {
    return this._apellido;
}

set apellido(value: string) {
    this._apellido = value;
}
get correo(): string {
    return this._correo;
}

set correo(value: string) {
    this._correo = value;
}
get telefono(): number {
    return this._telefono;
}

set telefono(value: number) {
    this._telefono = value;
}

get idempresaext(): number {
    return this._idempresaext;
}

set idempresaext(value: number) {
    this._idempresaext = value;
}
}

