import * as fs from "fs";
import * as $ from "jquery";
import {RecordRenderer} from "../RecordRenderer";

// const mock = jest.genMockFromModule("Hydra");

const Mock = jest.fn(() => ({
    getBibliographicDetails: jest.fn((ids) => {
        return readFixture("bibliographicResource");
    }),
    getContributorDetails: jest.fn((ids) => {
        return readFixture("contributors");
    }),
}));

const cut: RecordRenderer = new RecordRenderer("");
cut.client = new Mock();

function readFixture(fileName: string) {
    return new Promise((resolve, reject) =>
        fs.readFile(`themes/sbvfrd/ts/tests/fixtures/${ fileName }.json`, "utf8",
            (err: any, data: any) => {
                if (err) {
                    reject(err);
                }
                resolve(
                    JSON.parse(data));
            }));
}

it("should create Html", () => {
    expect.assertions(1);

    const templateFn = (p: any): string => {
        return `${p.lastName}, ${p.firstName}`;
    };

    const actual = cut.getContributorHtml(
        readFixture("person-5f679432-5f41-3bd8-a19f-8a20c4431aea"), templateFn);
    return expect(actual).resolves.toContain("Bamber, David");
});

it("Html should contain list element with contributors", () => {
    const body = document.getElementsByTagName("body")[0];
    const list = document.createElement("ul");
    body.appendChild(list);

    expect.assertions(2);

    const contributorsTemplate = (p: any) => {
        return `<li>${p.firstName}</li>`;
    };
    const contributorsList = $(list)[0];

    return cut.render("023426233", contributorsTemplate, contributorsList, null, null)
        .then((html: HTMLElement[]) => {
            const actual: JQuery<HTMLElement> = $(html[0]);
            expect(actual.children("li").length).toBe(10);
            expect(actual.find("li").get(0).innerHTML).toEqual("David");
        })
        ;
});
