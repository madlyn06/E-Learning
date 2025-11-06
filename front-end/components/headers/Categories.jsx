import { useAppData } from "@/context/AppDataContext";
import Link from "next/link";
import React from "react";

export default function Categories() {
  const app = useAppData();
  const categories = app?.categories?.tree || [];
  return (
    <ul>
      {categories.length > 0 && categories.map((elm, i) => (
        <li key={i} className="has-children">
          <a className="item" href="#">
            {elm.label}
          </a>
          <ul className="sub-menu">
            {elm.children.map((elm2, i2) => (
              <li key={i2}>
                <Link className="item" href={elm2.url}>
                  {elm2.label}
                </Link>
              </li>
            ))}
          </ul>
        </li>
      ))}
    </ul>
  );
}
